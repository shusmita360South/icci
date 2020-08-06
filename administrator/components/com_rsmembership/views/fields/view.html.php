<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewFields extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state	 	= $this->get('State');
		$this->ordering	 	= $this->get('Ordering');

		$this->addToolbar();

		$this->filterbar = $this->get('FilterBar');
		$this->sidebar 	 = $this->get('SideBar');
		
		parent::display($tpl);
	}

	protected function addToolbar() 
	{
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_FIELDS'),'fields');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('fields');
		
		JToolBarHelper::addNew('field.add');
		JToolBarHelper::editList('field.edit');
		
		JToolBarHelper::spacer();
		JToolbarHelper::publish('fields.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('fields.unpublish', 'JTOOLBAR_UNPUBLISH', true);

		JToolBarHelper::spacer();
		JToolBarHelper::deleteList('COM_RSMEMBERSHIP_CONFIRM_DELETE','fields.delete');
	}
}