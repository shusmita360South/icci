<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipModelRSMembership extends JModelLegacy
{	
	public function getCode() {
		return RSMembershipHelper::getConfig('global_register_code');
	}
	
	public function _getDefaultFilters() 
	{
		$db 		= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$filters 	= array();

		// set the default values for fields in xml
		$filters['report'] 				= 'report_2';
		$filters['from_date'] 			= '';
		$filters['to_date'] 			= RSMembershipHelper::showDate(JFactory::getDate()->toUnix(), 'Y-m-d');
		$filters['unit']				= 'day';
		$filters['user_id'] 			= '';
		$filters['memberships'] 		= array_keys(RSMembershipHelper::getMembershipsList(false));
		$filters['status_memberships'] 	= array(0,1,2,3);
		$filters['status_transactions'] = array('pending', 'completed', 'denied');
		$filters['price_from'] 			= 0;
		$filters['price_to'] 			= '';
		$filters['transaction_types'] 	= array('new', 'upgrade', 'addextra', 'renew');

		$query->select('DISTINCT(gateway)')->from($db->qn('#__rsmembership_transactions'))->order($db->qn('gateway').' ASC');
		$db->setQuery($query);
		$filters['gateways'] 			= $db->loadColumn();

		return $filters;
	}
	
	public function getReportData() 
	{
		$transaction_filters = $this->_getDefaultFilters();
		$reports_model 		 = $this->getInstance('Reports', 'RSMembershipModel');

		return $reports_model->getReportData($transaction_filters);
	}
}