<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2020 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsmembershipControllerMytransaction extends JControllerForm
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        $user = JFactory::getUser();
        if ( $user->get('guest') )
        {
            $link 		 = JUri::getInstance();
            $link 		 = base64_encode($link);
            $user_option = 'com_users';

            JFactory::getApplication()->redirect('index.php?option='.$user_option.'&view=login&return='.$link);
        }
    }

    public function outputinvoice() {
        $app = JFactory::getApplication();

        $transaction_id = $app->input->get('id', 0);

        require_once JPATH_ADMINISTRATOR . '/components/com_rsmembership/helpers/invoice.php';

        try {
            // get the invoice
            $invoice = RSMembershipInvoice::getInstance($transaction_id);

            $invoice->outputInvoicePdf('download');
        } catch (Exception $e) {
            $app->enqueueMessage($e->getMessage(),'error');
            $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mytransactions', false));
        }
    }
}