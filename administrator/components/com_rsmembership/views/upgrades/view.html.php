<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewUpgrades extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state	 	= $this->get('State');

		$this->addToolbar();

		$this->filterbar = $this->get('FilterBar');
		$this->sidebar 	 = $this->get('SideBar');
		
		parent::display($tpl);
	}

	protected function addToolbar() 
	{
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_UPGRADES'),'upgrades');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('upgrades');

		JToolBarHelper::addNew('upgrade.add');
		JToolBarHelper::editList('upgrade.edit');

		JToolBarHelper::spacer();
		JToolbarHelper::publish('upgrades.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('upgrades.unpublish', 'JTOOLBAR_UNPUBLISH', true);

		JToolBarHelper::spacer();
		JToolBarHelper::deleteList('COM_RSMEMBERSHIP_CONFIRM_DELETE','upgrades.delete');
	}
}