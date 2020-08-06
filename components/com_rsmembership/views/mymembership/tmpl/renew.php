<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

JText::script('COM_RSMEMBERSHIP_THERE_WAS_AN_ERROR');
if (!empty($this->membershipterms)) {
    JText::script('COM_RSMEMBERSHIP_PLEASE_AGREE_MEMBERSHIP');
}

$total = $this->membership->price;
?>

<div class="item-page">
    <?php if ($this->params->get('show_page_heading', 1)) { ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php } ?>
</div>

<form method="post" class="rsmembership_form" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=mymembership.renewpaymentredirect'); ?>" name="membershipForm" onsubmit="return RSMembership.subscribe.validate_subscribe(this);" id="rsm_renew_form">
    <div class="item-page">
        <div class="page-header"><h3><?php echo JText::_('COM_RSMEMBERSHIP_PURCHASE_INFORMATION'); ?></h3></div>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
            <tr>
                <td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP'); ?>:</td>
                <td><?php echo $this->membership->name; ?> - <?php echo RSMembershipHelper::getPriceFormat($this->membership->price); ?></td>
            </tr>
            <?php if (!empty($this->extras)) foreach ($this->extras as $extra) {
                $total += $extra->price; ?>
                <tr>
                    <td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_EXTRA'); ?>:</td>
                    <td><?php echo $extra->name; ?> - <?php echo RSMembershipHelper::getPriceFormat($extra->price); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_TOTAL_COST'); ?>:</td>
                <td><?php echo RSMembershipHelper::getPriceFormat($total); ?></td>
            </tr>
        </table>
    </div>

    <div class="item-page">
        <div class="page-header"><h3><?php echo JText::_('COM_RSMEMBERSHIP_ACCOUNT_INFORMATION'); ?></h3></div>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
            <tr>
                <td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_NAME'); ?>:</td>
                <td><?php echo $this->escape($this->user->get('name')); ?></td>
            </tr>
            <tr>
                <td height="40"><?php echo JText::_( 'COM_RSMEMBERSHIP_EMAIL' ); ?>:</td>
                <td><?php echo $this->escape($this->user->get('email')); ?></td>
            </tr>
            <?php foreach ($this->fields as $field) {
                $hidden = (isset($field[2]) && $field[2] == 'hidden') ? true : false;
                ?>
                <tr<?php echo ($hidden ? ' style="display:none"':'')?>>
                    <td height="40"><?php echo $field[0]; ?></td>
                    <td><?php echo $field[1]; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <?php if (count($this->membership_fields)) { ?>
        <div class="item-page">
            <h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_INFORMATION'); ?></h3>
            <table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
                <?php foreach ($this->membership_fields as $field) {
                    $hidden = (isset($field[2]) && $field[2] == 'hidden') ? true : false;
                    ?>
                    <tr<?php echo ($hidden ? ' style="display:none"':'')?>>
                        <td width="30%" height="40"><?php echo $field[0]; ?></td>
                        <td><?php echo $field[1]; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>

    <?php if ($total > 0) { ?>
        <div class="item-page">
            <div class="page-header"><h3><?php echo JText::_('COM_RSMEMBERSHIP_PAYMENT_INFORMATION'); ?></h3></div>
            <table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
                <tr>
                    <td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_PAY_WITH'); ?>:</td>
                    <td>
                        <?php
                        $i = 0;
                        if (!empty($this->payments))
                        {
                            foreach ($this->payments as $plugin => $paymentdetails) {
                                $i++;
                                $tax_value = '';
                                $paymentname = '';
                                if (is_array($paymentdetails))
                                {
                                    if ($paymentdetails['tax_details'])
                                    {
                                        if ($paymentdetails['tax_details']['tax_type'] == 0)
                                        {
                                            $tax_value = $total * ($paymentdetails['tax_details']['tax_value'] / 100);
                                            $tax_value = JText::sprintf('COM_RSMEMBERSHIP_PAY_TAX_VALUE_PERCENT', $tax_value, RSMembershipHelper::getPriceFormat($tax_value), $paymentdetails['tax_details']['tax_value']);
                                        }
                                        else
                                        {
                                            $tax_value = JText::sprintf('COM_RSMEMBERSHIP_PAY_TAX_VALUE_FIXED', $paymentdetails['tax_details']['tax_value'], RSMembershipHelper::getPriceFormat($paymentdetails['tax_details']['tax_value']));
                                        }
                                    }
                                    $paymentname = $paymentdetails['name'];
                                }
                                else
                                {
                                    $paymentname = $paymentdetails;
                                }
                                ?>
                                <p><input <?php echo $i == 1 ? 'checked="checked"' : ''; ?> type="radio" name="payment" value="<?php echo $this->escape($plugin); ?>" id="payment<?php echo $i; ?>" class="pull-left" /> <label for="payment<?php echo $i; ?>"><?php echo $this->escape($paymentname).$tax_value; ?></label></p>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="item-page">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
                <tr>
                    <td width="30%" height="40"><strong><?php echo JText::_('COM_RSMEMBERSHIP_GRAND_TOTAL'); ?></strong>:</td>
                    <td><span id="rsm_grand_total" data-fixedvalue="<?php echo $total;?>"><?php echo RSMembershipHelper::getPriceFormat($total); ?></span></td>
                </tr>
            </table>
        </div>
    <?php } ?>

    <?php if (!empty($this->membershipterms)) { ?>
        <div class="item-page">
            <h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_TERM'); ?></h3>
            <div id="rsm_terms_frame">
                <div class="item-page">
                    <div id="rsm_terms_container">
                        <h1><?php echo $this->escape($this->membershipterms->name); ?></h1>
                        <?php
                        if (RSMembershipHelper::getConfig('trigger_content_plugins')) {
                            $this->membershipterms->description = JHtml::_('content.prepare', $this->membershipterms->description);
                        }
                        echo $this->membershipterms->description;
                        ?>
                    </div> <!-- rsm_terms_container -->
                </div>
            </div>
            <input type="checkbox" id="rsm_checkbox_agree" class="pull-left" name="i_agree_to_terms" value="1" /> <label for="rsm_checkbox_agree"><?php echo JText::_('COM_RSMEMBERSHIP_I_AGREE'); ?> (<?php echo $this->escape($this->membershipterms->name); ?>)</label>
        </div>
    <?php } ?>

    <div class="form-actions">
        <button type="button" class="button btn pull-left" onclick="document.location='<?php echo JRoute::_('index.php?option=com_rsmembership&view=mymembership&cid='.$this->cid); ?>'" name="Cancel"><?php echo JText::_('COM_RSMEMBERSHIP_BACK'); ?></button>
        <button type="submit" class="button btn btn-success pull-right"><?php echo JText::_('COM_RSMEMBERSHIP_RENEW'); ?></button>
    </div>
    <?php echo $this->token; ?>
    <input type="hidden" name="option" value="com_rsmembership" />
    <input type="hidden" name="view" value="mymembership" />
    <input type="hidden" name="task" value="mymembership.renewpaymentredirect" />
    <input type="hidden" name="cid" value="<?php echo $this->cid; ?>" />
</form>

<?php
echo RSMembershipHelper::renderMagnificPopup('rsmembershipModal', array(
    'url' 	   => false,
    'height'   => 400
));
?>
