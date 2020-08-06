<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipTableCoupon extends JTable
{
	public function __construct(& $db) 
	{
		parent::__construct('#__rsmembership_coupons', 'id', $db);
	}
	
	public function check() {
		$db = JFactory::getDbo();
		
		// Must check if coupon hasn't been already used
		$query = $db->getQuery(true);
		
		$query->select($db->qn('id'))
			->from($db->qn('#__rsmembership_coupons'))
			->where($db->qn('name').'='.$db->q($this->name));
			
		if ($this->id) {
			$query->where($db->qn('id').' != '.$db->q($this->id));
		}
		
		if ($db->setQuery($query)->loadResult()) {
			$this->setError(JText::_('COM_RSMEMBERSHIP_COUPON_ALREADY_USED'));
			return false;
		}
		
		return true;
	}
}