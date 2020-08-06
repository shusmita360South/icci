<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelMembership extends JModelItem
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getItem($pk = null)
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$cid	= JFactory::getApplication()->input->get('cid', 0, 'int');

		$item 	= $this->getTable('Membership', 'RSMembershipTable');
		$item->load($cid);

		$query->select($db->qn('name'))->from($db->qn('#__rsmembership_categories'))->where($db->qn('id').' = '.$db->q($item->category_id));
		$db->setQuery($query);
		$item->category_name = $db->loadResult();

		if ( $item->use_trial_period ) 
			$item->price = $item->trial_price;

		// disable buy button and out of stock warning
		if ($item->stock == -1) 
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSMEMBERSHIP_OUT_OF_STOCK'), 'warning');
		}

		return $item;
	}
}