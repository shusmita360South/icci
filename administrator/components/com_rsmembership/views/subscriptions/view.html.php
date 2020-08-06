<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewSubscriptions extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state	 	= $this->get('State');
		$this->memberships	= $this->get('Memberships');
		$this->context		= $this->get('Context');
		$this->totalItems   = $this->get('TotalItems');
		$this->extraValues  = $this->get('ExtraValues');

		$this->addToolbar();

		$this->filterbar = $this->get('FilterBar');
		$this->sidebar 	 = $this->get('SideBar');

		JHtml::_('script', 'com_rsmembership/admin/export.js', array('relative' => true, 'version' => 'auto'));
		
		parent::display($tpl);
	}

	protected function isNullDate($date) {
		static $nullDate;
		if (!$nullDate) {
			$nullDate = JFactory::getDbo()->getNullDate();
		}
		
		return ($date == $nullDate);
	}
	
	protected function addToolbar() 
	{
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_SUBSCRIPTIONS'),'subscriptions');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('subscriptions');
		
		JToolBarHelper::custom('subscriptions.exportcsv', 'download.png', 'download_f2.png', 'COM_RSMEMBERSHIP_EXPORT', false);
	}
}