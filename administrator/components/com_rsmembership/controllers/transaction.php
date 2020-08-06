<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerTransaction extends JControllerForm
{
	public function __construct() {
		parent::__construct();
	}

	public function cancel($key = null) 
	{
		parent::cancel($key);
		
	}

	public function outputinvoice() {
		$app = JFactory::getApplication();

		$transaction_id = $app->input->get('id', 0);
		$user_id        = $app->input->get('user_id', 0);

		require_once JPATH_ADMINISTRATOR . '/components/com_rsmembership/helpers/invoice.php';

		try {
			// get the invoice
			$invoice = RSMembershipInvoice::getInstance($transaction_id);

			// set the user id
			$invoice->setUserId($user_id);

			$invoice->outputInvoicePdf('download');
		} catch (Exception $e) {
			$app->enqueueMessage($e->getMessage(),'error');
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=transactions', false));
		}
	}

}