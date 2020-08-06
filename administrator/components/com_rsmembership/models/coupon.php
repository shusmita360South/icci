<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipModelCoupon extends JModelAdmin
{
	protected $text_prefix = 'COM_RSMEMBERSHIP';

	public function getTable($type = 'Coupon', $prefix = 'RSMembershipTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_rsmembership.coupon', 'coupon', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
			return false;

		return $form;
	}

	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_rsmembership.edit.coupon.data', array());

		if (empty($data)) 
			$data = $this->getItem();

		return $data;
	}

	public function getItem($pk = null)
	{
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		$item 	= parent::getItem($pk);

		$query->select($db->qn('membership_id'))->from($db->qn('#__rsmembership_coupon_items'))->where($db->qn('coupon_id').' = '.$db->q($item->id));
		$db->setQuery($query);

		// load coupon items
		$item->used_for = array();
		foreach($db->loadObjectList() as $coupon_item) 
			$item->used_for[] = $coupon_item->membership_id;

		return $item;
	}

	public function getRSFieldset() 
	{
		require_once JPATH_COMPONENT.'/helpers/adapters/fieldset.php';

		$fieldset = new RSFieldset();
		return $fieldset;
	}
	
	public function validate($form, $data, $group = NULL) {
		if (!empty($data))
		{			
			try
			{
				$data['name'] = trim($data['name']);
				
				// check that the coupon is not empty
				if (!strlen($data['name']))
				{
					throw new Exception(JText::_('COM_RSMEMBERSHIP_COUPON_NAME_ERROR'));
				}

				$discount_price = (float) $data['discount_price'];

				// check to see that is not a negative value
				if ($discount_price < 0)
				{
					throw new Exception(JText::_('COM_RSMEMBERSHIP_COUPON_NEGATIVE_VALUE_ERROR'));
				}

				// check to see if the discount does not exceed 100%
				if (isset($data['discount_type']) && $data['discount_type'] == '0' && $discount_price > 100)
				{
					throw new Exception(JText::_('COM_RSMEMBERSHIP_COUPON_DISCOUNT_PERCENT_ERROR'));
				}
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				return false;
			}
		}

		return $data;
	}

	public function save($data) 
	{
		$db 		= JFactory::getDBO();
		$query		= $db->getQuery(true);

		if (empty($data['id'])) {
			$data['date_added'] = JFactory::getDate()->toSql();
		}

		if (!parent::save($data)) {
			return false;
		}

		$coupon_id 	= $this->getState($this->getName() . '.id', 'id');

		// delete
		$query->delete($db->qn('#__rsmembership_coupon_items'))->where($db->qn('coupon_id').' = '.$db->q($coupon_id));
		$db->setQuery($query);
		$db->execute();

		// insert in coupon_items 
		if (!empty($data['used_for'])) 
		{
			foreach($data['used_for'] as $membership_item) {
				$query->clear();
				$query->insert($db->qn('#__rsmembership_coupon_items'))->set($db->qn('coupon_id').' = '.$db->q($coupon_id).', '.$db->qn('membership_id').' = '.$db->q($membership_item));
				$db->setQuery($query);
				$db->execute();
			}
		}

		return true;
	}

	public function delete(&$cids)
	{
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);

		// delete coupon items
		$query->delete($db->qn('#__rsmembership_coupon_items'))->where($db->qn('coupon_id').' IN ('.RSMembershipHelper::quoteImplode($cids).')');
		$db->setQuery($query);
		$db->execute();

		parent::delete($cids);

		return true;
	}

}