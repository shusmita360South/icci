<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2020 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');


/**
 * Invoice generator class.
 */
class RSMembershipInvoice
{
    protected $document;
    protected $user_id;

    protected $transaction_id;
    protected static $font;
    protected static $font_size;

    public function __construct($transaction_id = null)
    {
        if (empty($transaction_id)) {
            throw new Exception(JText::_('COM_MEMBERSHIP_ERROR_INVOICE_NO_TRANSACTION'), 500);
        }

        $this->transaction_id = $transaction_id;
    }

    public static function getInstance($transaction_id = null) {
        static $invoice_instances = array();

        if (!isset($invoice_instances[$transaction_id])) {
            $invoice_instances[$transaction_id] = new RSMembershipInvoice($transaction_id);
        }

        return $invoice_instances[$transaction_id];
    }

    /**
     * Method to get a table object, load it if necessary.
     *
     * @access public
     *
     * @param string $type
     * @param string $prefix
     * @param array $config
     *
     * @return object
     */
    public function getTable( $type = 'Transaction', $prefix = 'RSMembershipTable', $config = array() )
    {
        JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to set an User ID. Used only in the administration part
     *
     * @access public
     *
     * @param int $user_id The id of the User ID.
     *
     */
    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    /**
     * Method to get a single record.
     *
     * @access public
     *
     *
     * @return mixed Object on success, false on failure.
     */
    public function getItem()
    {
        $table = $this->getTable();
        $table->load( array( 'id' => $this->transaction_id, 'user_id' => $this->user_id ) );

        return $table->id ? $table : null;
    }

    /**
     * Method to get a single record for coupon.
     *
     * @access protected
     *
     *
     * @return mixed Object on success, false on failure.
     */
    public function getCoupon($coupon = '')
    {
        $table = $this->getTable('Coupon');
        $table->load( array( 'name' => $coupon ) );

        return $table->id ? $table : null;
    }

    /**
     * Method to get the invoice template.
     *
     * @access public
     *
     *
     * @return mixed Object
     */
    public function getTemplate()
    {
        if ($transaction = $this->getItem())
        {
            $membership_id_model = 0;

            $params = RSMembershipHelper::parseParams($transaction->params);

            switch($transaction->type)
            {
                case 'new':
                case 'renew':
                case 'addextra':
                    $membership_id_model = !empty($params['membership_id']) ? $params['membership_id'] : 0;
                break;

                case 'upgrade':
                    $membership_id_model = !empty($params['to_id']) ? $params['to_id'] : 0;
                break;
            }

            if (!empty($membership_id_model))
            {
                if ($membership_data = RSMembership::getMembershipData($membership_id_model))
                {
                    // return empty because the membership does`nt use the invoice
                    if (empty($membership_data->use_membership_invoice))
                    {
                        return '';
                    }

                    $custom = $membership_data->membership_invoice_type == 'global' ? false : true;

                    $padding    = $custom ? (int) $membership_data->membership_invoice_padding : RSMembershipHelper::getConfig('general_invoice_padding', 0);
                    $title      = $custom ? $membership_data->membership_invoice_title :  RSMembershipHelper::getConfig('general_invoice_title', JText::_('COM_RSMEMBERSHIP_INVOICE_TITLE'));
                    $template   = $custom ? $membership_data->membership_invoice_layout :  RSMembershipHelper::getConfig('general_invoice_layout', '');

                    self::$font        = $custom ? $membership_data->membership_invoice_font :  RSMembershipHelper::getConfig('general_invoice_pdf_font', 'freesans');
                    self::$font_size   = $custom ? (int) $membership_data->membership_invoice_pdf_font_size :  (int) RSMembershipHelper::getConfig('general_invoice_pdf_font_size', '12');

                    // skip if there is no template defined
                    if (empty($template))
                    {
                        return '';
                    }

                    // get user details
                    $user_data = unserialize($transaction->user_data);
                    $user_email = $transaction->user_email;

                    $extras = '';
                    $transaction_data = !empty($transaction->transaction_data) ? json_decode($transaction->transaction_data) : array();

                    if (!is_array($transaction_data)) {
                        $transaction_data = (array) $transaction_data;
                    }

                    if ($transaction->type == 'new' && $membership_data) {
                        if (empty($transaction_data) || !isset($transaction_data['membership'])) {
                            // get the price of the membership
                            $transaction_data['membership'] = (object) array('price' => ($membership_data->use_trial_period ? $membership_data->trial_price : $membership_data->price));
                        }
                    }

                    if (!empty($params['extras']))
                    {
                        $extras = RSMembershipHelper::getExtrasNames($params['extras']);
                        if (empty($transaction_data) || !isset($transaction_data['extras'])) {
                            $transaction_data['extras'] = array();

                            $db = JFactory::getDbo();
                            foreach ($params['extras'] as $extra_id) {
                                $query = $db->getQuery(true);

                                $query->select($db->qn('price'))
                                    ->select($db->qn('name'))
                                    ->from($db->qn('#__rsmembership_extra_values'))
                                    ->where($db->qn('id') . ' = ' . $db->q($extra_id));

                                $db->setQuery($query);
                                if ($e_data = $db->loadObject()) {

                                    $transaction_data['extras'][] = $e_data;
                                }
                            }
                        }
                    }

                    $percent_value = 0;
                    if ($transaction->tax_type == 0 && $transaction->tax_value > 0)
                    {
                        $item_price = $transaction->price - $transaction->tax_value;
                        $percent_value = ($transaction->tax_value * 100) / $item_price;
                        $percent_value = round($percent_value, 2);
                    }

                    // if there is no tax value then the tax-type placeholder needs to be empty
                    $tax_type_placeholder = '';
                    if ($transaction->tax_value > 0)
                    {
                        $tax_type_placeholder = $transaction->tax_type == 0 ? JText::sprintf('COM_RSMEMBERSHIP_INVOICE_TAX_TYPE_PERCENT', $percent_value.'%') : JText::_('COM_RSMEMBERSHIP_INVOICE_TAX_TYPE_FIXED');
                    }

                    // get the coupon details if any
                    $coupon = false;
                    $discount_value = '';
                    $discount_type_placeholder = '';
                    if (!empty($transaction->coupon)) {

                        if(isset($transaction_data['discount']) && $transaction_data['discount']->price > 0) {
                            $coupon = new stdClass();
                            $coupon->discount_type = $transaction_data['discount']->type;
                            $coupon->discount_price = $transaction_data['discount']->price;
                        }

                        // if there is no data in the transaction check to see if the coupon still exists
                        if (!$coupon) {
                            $coupon  = $this->getCoupon($transaction->coupon);
                        }

                        if ($coupon) {
                            $price = (float) $transaction->price;

                            if (isset($transaction_data['membership'])) {
                                $price = (float) $transaction_data['membership']->price;
                            } else {
                                $price = $this->priceWithoutTax($price, $transaction->tax_value);
                            }

                            $discount_value = $this->discountAmount($price, $coupon);
                            $discount_value = $discount_value > 0 ? RSMembershipHelper::getPriceFormat($discount_value) : '100%';
							
							if ($coupon->discount_type == 0) {
                                $coupon->discount_price = (float) $coupon->discount_price;
                            }
                            
                            $discount_type_placeholder = $coupon->discount_type == 0 ? JText::sprintf('COM_RSMEMBERSHIP_INVOICE_DISCOUNT_TYPE_PERCENT',  $coupon->discount_price . '%') : JText::_('COM_RSMEMBERSHIP_INVOICE_DISCOUNT_TYPE_FIXED');
                        }
                    }

                    // build the placeholders and values
                    $placeholders = array(
                        '{invoice_id}'		 => str_pad($transaction->id, $padding, 0, STR_PAD_LEFT),
                        '{tax_type}' 		 => $tax_type_placeholder,
                        '{tax_value}' 	     => $transaction->tax_value > 0 ? $transaction->tax_value : '',
                        '{membership}' 		 => $membership_data->name,
                        '{category}'		 => RSMembershipHelper::getCategoryName($membership_data->category_id),
                        '{extras}' 			 => $extras,
                        '{email}' 			 => $user_email,
                        '{name}' 			 => $user_data->name,
                        '{username}' 		 => (isset($user_data->username) ? $user_data->username : ''),
                        '{total_price}' 	 => RSMembershipHelper::getPriceFormat($transaction->price),
                        '{coupon}' 			 => $transaction->coupon,
                        '{discount_type}' 	 => $discount_type_placeholder,
                        '{discount_value}' 	 => $discount_value,
                        '{payment}' 		 => $transaction->gateway,
                        '{transaction_id}' 	 => $transaction->id,
                        '{transaction_hash}' => $transaction->hash,
						'{date_purchased}' 	 => RSMembershipHelper::showDate($transaction->date),
                        '{site_name}' 	     => JFactory::getConfig()->get('sitename'),
                        '{site_url}' 	     => JUri::root(),
                        '{membership_from}'  => ''
                    );

                    if ($transaction->type == 'upgrade') {
                        $placeholders['{membership_from}'] = RSMembership::getMembershipData($params['from_id'])->name;
                    }

                    // build the sold item
                    $item_sold = '';
                    switch($transaction->type)
                    {
                        case 'new':
                            $item_sold = $membership_data->name;
                        break;

                        case 'upgrade':
                            $item_sold = $placeholders['{membership_from}'].' -&gt; '.$membership_data->name;
                        break;

                        case 'addextra':
                            if (!empty($extras))
                            {
                                $item_sold = '- '.$extras;
                            }
                        break;

                        case 'renew':
                            $item_sold = $membership_data->name;
                        break;
                    }

                    $placeholders['{invoice_transaction_table}'] = $this->buildTransactionTable($transaction,$item_sold, $percent_value, $coupon, $transaction_data);

                    $fields 			= RSMembership::getCustomFields();
                    $membership_fields  = RSMembership::getCustomMembershipFields($membership_id_model);
                    $all_fields = array_merge($fields,$membership_fields);

                    foreach ($all_fields as $field)
                    {
                        $name 	= $field->name;
                        $object = (isset($user_data->fields[$name]) ? 'fields' : 'membership_fields');
                        if ( isset($user_data->fields[$name]) || isset($user_data->membership_fields[$name]))
                            $placeholders['{'.$name.'}'] = is_array($user_data->{$object}[$name]) ? implode("\n", $user_data->{$object}[$name]) : $user_data->{$object}[$name];
                        else
                            $placeholders['{'.$name.'}'] = '';
                    }

                    $replace = array_keys($placeholders);
                    $with 	 = array_values($placeholders);

                    // handle placeholders for the title
                    $this->document = new stdClass();

                    $title = $this->processConditional($title, $replace, $with);
                    $this->document->title = str_replace($replace, $with, $title);

                    // handle placeholders for the invoice content
                    $template = $this->processConditional($template, $replace, $with);
                    $template = str_replace($replace, $with, $template);

                    return $template;
                }
            }
        }

        return '';
    }

    protected function processConditional($string, $replace, $with)
    {
        if ( strpos($string, '{/if}') !== false )
        {
            $condition 	= '[a-z0-9\-\_]+';
            $inner = '((?:(?!{/?if).)*?)';
            $pattern = '#{if\s?(' . $condition . ')}' . $inner . '{/if}#is';

            // remove the brackets from the placeholders
            $keys = array();
            foreach($replace as $i => $value) {
                $replace[$i] = str_replace(array('{', '}'), '', $value);
                $keys[$replace[$i]] = $i;
            }

            while ( preg_match($pattern, $string, $match) ) {
                $field_name = $match[1];
                $content = $match[2];

                $replace_cond = !in_array($field_name, $replace) || $with[$keys[$field_name]] == '' ? '' : addcslashes($content, '$');

                $newline = '{/if}[\r]?[\n]?';

                // If empty value remove whole line else show line but remove pseudo-code.
                $string = preg_replace(str_replace('{/if}', $newline, $pattern), $replace_cond, $string, 1);
            }
        }

        return $string;
    }

    /**
     * Method to get the document related object.
     *
     * @access public
     *
     *
     * @return mixed Object
     */
    public function getDocument()
    {
        return $this->document;
    }

    protected function escape($string)
    {
        return htmlentities($string, ENT_COMPAT, 'UTF-8');
    }

    protected function discountAmount($price, $coupon) {
        $price = (float) $price;
        if (!empty($coupon->discount_type)) {
            $coupon_value = (float)$coupon->discount_price;
            if ($coupon_value > $price) {
                $coupon_value = $price;
            }
        } else {
            $discount = (float)$coupon->discount_price;
            $coupon_value = ($price * $discount) / 100;

            if ($coupon_value > $price) {
                $coupon_value = $price;
            }
        }

        return $coupon_value;
    }

    protected function priceWithoutTax($price, $tax_value) {
        $price = (float) $price;
        $tax_value = (float) $tax_value;

        if ($tax_value > 0) {
            $price = $price - $tax_value;
        }

        return $price;
    }

    /**
     * Method to build the transaction table for the proper placeholder.
     *
     * @access protected
     *
     *
     * @return string
     */
    protected function buildTransactionTable($transaction, $item_sold, $percent_value, $coupon = false, $transaction_data = array())
    {
        $price = (float) $transaction->price;

        if (isset($transaction_data['membership'])) {
            $price = (float) $transaction_data['membership']->price;
        } else {
            $price = $this->priceWithoutTax($price, $transaction->tax_value);
        }

        // add to the total the coupon if any
        $coupon_value = 0;
        if (!empty($transaction->coupon) && $coupon) {
            $coupon_value = $this->discountAmount($price, $coupon);
        }

        $extras_total = 0;
        if (!empty($transaction_data['extras']) && isset($transaction_data['membership'])) {
            foreach ($transaction_data['extras'] as $extra) {
                $extras_total += (float)$extra->price;
            }
        }

        $subtotal = RSMembershipHelper::getPriceFormat(($price + $extras_total - $coupon_value));
        $price = RSMembershipHelper::getPriceFormat($price);
        $total = RSMembershipHelper::getPriceFormat($transaction->price);

        $item_sold = JText::_('COM_RSMEMBERSHIP_TRANSACTION_'.strtoupper($transaction->type)).' '.$item_sold;

        // transaction
        $table = '<table width="100%" border="1" cellpadding="3" cellspacing="1" class="invoice_table">
                <thead>
                    <tr>
                        <th class="package">'.JText::_('COM_RSMEMBERSHIP_INVOICE_ITEM_SOLD').'</th>
                        <th class="price">'.JText::_('COM_RSMEMBERSHIP_INVOICE_PRICE').'</th>
                        <th class="quantity">'.JText::_('COM_RSMEMBERSHIP_INVOICE_QTY').'</th>
                        <th class="total">'.JText::_('COM_RSMEMBERSHIP_INVOICE_TOTAL').'</th>
                    </tr>
                </thead>
                <tbody>';

        if ($transaction->type != 'addextra') {
            $table .= ' <tr>
                        <td class="package">' . $this->escape($item_sold) . '</td>
                        <td class="price">' . $this->escape($price) . '</td>
                        <td class="quantity">1</td>
                        <td class="total">' . $this->escape($price) . '</td>
                    </tr>';
        }
        // coupons
        if (!empty($transaction->coupon) && $coupon) {
            $coupon_value = RSMembershipHelper::getPriceFormat($coupon_value);
            $table .=' <tr class="coupon">
                        <td class="package">'.JText::sprintf('COM_RSMEMBERSHIP_INVOICE_COUPON', $transaction->coupon).' '.($coupon->discount_type == 0 ? JText::sprintf('COM_RSMEMBERSHIP_INVOICE_TAX_TYPE_TABLE_PERCENT', $coupon->discount_price.'%') : '').'</td>
                        <td class="price">-'.$this->escape($coupon_value).'</td>
                        <td class="quantity">1</td>
                        <td class="total">-'.$this->escape($coupon_value).'</td>
                    </tr>';
        }

        // extras
        if (!empty($transaction_data['extras'])) {
            foreach ($transaction_data['extras'] as $extra) {
                $extra_price = RSMembershipHelper::getPriceFormat($extra->price);
                $table .=' <tr class="extra">
                        <td class="package">'.($transaction->type == 'addextra' ? $this->escape($item_sold) : JText::_('COM_RSMEMBERSHIP_INVOICE_EXTRA').' '.$this->escape($extra->name)).'</td>
                        <td class="price">'.$this->escape($extra_price).'</td>
                        <td class="quantity">1</td>
                        <td class="total">'.$this->escape($extra_price).'</td>
                    </tr>';
            }
        }

        $table .= '<tr>
                        <td colspan="3" class="subtotal">'.JText::_('COM_RSMEMBERSHIP_INVOICE_SUBTOTAL').'</td>
                        <td class="subtotal_value">'.$this->escape($subtotal).'</td>
                    </tr>';

        if ($transaction->tax_value > 0)
        {
            $tax_value = RSMembershipHelper::getPriceFormat($transaction->tax_value);
            $table .='<tr>
                        <td colspan="3" class="tax">'.JText::_('COM_RSMEMBERSHIP_INVOICE_TAX').' '.($transaction->tax_type == 0 ? JText::sprintf('COM_RSMEMBERSHIP_INVOICE_TAX_TYPE_TABLE_PERCENT', $percent_value.'%') : '').'</td>
                        <td class="tax_value">'.$this->escape($tax_value).'</td>
                    </tr>';
        }

        $table .='<tr>
                        <td class="grand total" colspan="3" align="right">'.JText::_('COM_RSMEMBERSHIP_INVOICE_GRAND_TOTAL').'</td>
                        <td class="grand total">'.$this->escape($total).'</td>
                    </tr>
                </tbody>
            </table>';


        return $table;
    }


    /**
     * Method to output the Invoice PDF, as file, data string or only the template for placeholder use.
     *
     * @access public
     *
     * @throws \Exception on different errors
     */
    public function outputInvoicePdf($output_type = 'data')
    {
        // for the approval email this is generated twice, so we need to cache them
        static $invoice, $document;

        // If the access is made from the front and the user_id is not set assign it to the current logged in user
        if (JFactory::getApplication()->isClient('site') && is_null($this->user_id)) {
            $this->user_id = JFactory::getUser()->id;
        }

        if (is_null($this->user_id))
        {
            throw new Exception(JText::_('COM_MEMBERSHIP_ERROR_INVOICE_NO_USER_ID'), 500);
        }

        if (is_null($invoice))
        {
            $invoice = $this->getTemplate();
        }

        if (is_null($document))
        {
            $document = $this->getDocument();
        }

        if (empty($invoice) || is_null($document))
        {
            throw new Exception(JText::_('COM_MEMBERSHIP_ERROR_INVOICE_NO_TEMPLATE'), 500);
        }

        $filename = trim($document->title);

        if (!strlen($filename)) {
            $filename = JText::_('COM_RSMEMBERSHIP_GENERAL_INVOICE_NO_TITLE_DEFAULT');
        }

        // Return only the invoice template for placeholder use
        if ($output_type == 'placeholder')
        {
            return $invoice;
        }

        // Build root without Joomla! folder
        if ($folder = JUri::root(true))
        {
            $site_path = substr(str_replace(DIRECTORY_SEPARATOR, '/', JPATH_SITE), 0, -strlen($folder));
        }
        else
        {
            $site_path = str_replace(DIRECTORY_SEPARATOR, '/', JPATH_SITE);
        }

        // Add own CSS
        if ($css_path = realpath($site_path.'/' . JHtml::stylesheet('com_rsmembership/invoice.css', array('pathOnly' => true, 'relative' => true))))
        {
            $invoice = '<link rel="stylesheet" href="' . $this->escape($css_path) . '" type="text/css"/>' . $invoice;
        }

        $app = JFactory::getApplication();

        // Allow plugins to use their own PDF library
        $app->triggerEvent('rsm_onPDFView', array($invoice, $filename));

        /*
        * Setup external configuration options
        */
        define('K_TCPDF_EXTERNAL_CONFIG', true);
        define("K_PATH_MAIN", JPATH_ADMINISTRATOR . '/components/com_rsmembership/helpers/tcpdf');
        define("K_PATH_URL", JPATH_BASE);
        define("K_PATH_FONTS", K_PATH_MAIN.'/fonts/');
        define("K_PATH_CACHE", K_PATH_MAIN."/cache");
        define("K_PATH_URL_CACHE", K_PATH_URL."/cache");
        define("K_PATH_IMAGES", K_PATH_MAIN."/images");
        define("K_BLANK_IMAGE", K_PATH_IMAGES."/_blank.png");
        define("K_CELL_HEIGHT_RATIO", 1.25);
        define("K_TITLE_MAGNIFICATION", 1.3);
        define("K_SMALL_RATIO", 2/3);
        define("HEAD_MAGNIFICATION", 1.1);

        /*
         * Create the pdf document
         */

        if (!class_exists('TCPDF'))
        {
            require_once JPATH_ADMINISTRATOR . '/components/com_rsmembership/helpers/tcpdf/tcpdf.php';
        }

        $pdf = new TCPDF();
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 25);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->setImageScale(4);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $document = JFactory::getDocument();

        // Set PDF Metadata
        $pdf->SetCreator($document->getGenerator());
        $pdf->SetTitle($filename);
        $pdf->SetSubject($document->getDescription());
        $pdf->SetKeywords($document->getMetaData('keywords'));


        // Set RTL
        $lang = JFactory::getLanguage();
        $pdf->setRTL($lang->isRTL());

        // Set Font
        $font = is_null(self::$font) ? 'freesans' : self::$font;
        $font_size = is_null(self::$font_size) ? 12 : self::$font_size;
        $pdf->setFont($font, '', $font_size);

        $pdf->setHeaderFont(array($font, '', 10));
        $pdf->setFooterFont(array($font, '', 8));

        // Initialize PDF Document
        if (is_callable(array($pdf, 'AliasNbPages')))
        {
            $pdf->AliasNbPages();
        }
        $pdf->AddPage();

        $pdf->WriteHTML($invoice, true);
        $data = $pdf->Output('', 'S');

        if ($output_type == 'download')
        {
            // Build the PDF Document string from the document buffer
            header('Content-Type: application/pdf; charset=utf-8');
            header('Content-disposition: attachment; filename="' . $filename . '.pdf"', true);

            echo $data;

            $app->close();
        }

        return $data;
    }
}