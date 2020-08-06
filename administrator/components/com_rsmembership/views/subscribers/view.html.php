<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewSubscribers extends JViewLegacy
{
	public function display($tpl = null)
	{
		require_once JPATH_COMPONENT.'/helpers/rsmembership.php';
		
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state	 	= $this->get('State');
		$this->customFields = RSMembership::getCustomFields(array('showinsubscribers'=>1));
		$this->totalItems = $this->get('TotalItems');

		$this->addToolbar();

		$this->filterbar = $this->get('FilterBar');
		$this->sidebar 	 = $this->get('SideBar');

		JHtml::_('script', 'com_rsmembership/admin/export.js', array('relative' => true, 'version' => 'auto'));
		
		parent::display($tpl);
	}

	protected function addToolbar() 
	{		
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_SUBSCRIBERS'),'subscribers');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('subscribers');
		
		JToolBarHelper::editList('subscriber.edit');
		
		JToolBarHelper::spacer();
		JToolBarHelper::custom('subscribers.exportcsv', 'download.png', 'download_f2.png', 'COM_RSMEMBERSHIP_EXPORT', false);
	}
}