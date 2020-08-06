<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewLogs extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $filterbar;
	protected $sidebar;
	protected $isJ30;
	
	public function display($tpl=null) {
		$this->addToolBar();
		
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state 		= $this->get('State');
		
		$this->filterbar	= $this->get('FilterBar');		
		$this->sidebar 		= $this->get('SideBar');
		$this->dropdown		= $this->get('Dropdown');
		
		$this->userId		= $this->get('userId');
		
		parent::display($tpl);
	}
	
	protected function addToolBar() {
		// set title
		JToolBarHelper::title('RSMembership!', 'rsmembership');
		
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('subscribers');
		
		JToolBarHelper::deleteList('COM_RSMEMBERSHIP_CONFIRM_DELETE', 'logs.delete');
	}
}