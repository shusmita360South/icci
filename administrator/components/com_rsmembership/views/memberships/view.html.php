<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewMemberships extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state	 	= $this->get('State');

		$this->addToolbar();

		$this->filterbar = $this->get('FilterBar');
		$this->sidebar 	 = $this->get('SideBar');
		$this->ordering  = $this->get('Ordering');
		
		parent::display($tpl);
	}

	protected function addToolbar() 
	{
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_MEMBERSHIPS'),'memberships');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('memberships');

		JToolBarHelper::addNew('membership.add');
		JToolBarHelper::editList('membership.edit');
		
		JToolBarHelper::spacer();
		JToolBarHelper::custom('memberships.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
		
		JToolbarHelper::publish('memberships.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('memberships.unpublish', 'JTOOLBAR_UNPUBLISH', true);

		JToolBarHelper::spacer();
		JToolBarHelper::deleteList('COM_RSMEMBERSHIP_CONFIRM_DELETE','memberships.delete');
	}
}