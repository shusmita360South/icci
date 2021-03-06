<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewTransactions extends JViewLegacy
{
	public function display($tpl = null)
	{
		if ($this->getLayout() == 'log') 
			$this->log		 = $this->get('log');
		else 
		{
			$this->items 		= $this->get('Items');
			$this->pagination 	= $this->get('Pagination');
			$this->state	 	= $this->get('State');

			$this->addToolbar();

			$this->filterbar = $this->get('FilterBar');
			$this->sidebar 	 = $this->get('SideBar');
			$this->cache 	 = $this->get('cache');

			JToolBarHelper::custom('transactions.approve', 'approve', '', 'COM_RSMEMBERSHIP_APPROVE');
			JToolBarHelper::custom('transactions.deny', 'deny', '', 'COM_RSMEMBERSHIP_DENY');
			JToolBarHelper::spacer();
			JToolBarHelper::deleteList('COM_RSMEMBERSHIP_CONFIRM_DELETE','transactions.delete');
		}

		parent::display($tpl);
	}
	
	protected function addToolbar() 
	{
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_TRANSACTIONS'),'transactions');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('transactions');
	}
}