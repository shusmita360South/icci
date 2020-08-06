<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipViewRSMembership extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->addToolbar();

		$this->sidebar	= $this->get('SideBar');

		$this->code			= $this->get('code');
		$this->version		= (string) new RSMembershipVersion();

		// loading Google Charts
		$this->document->addScript('https://www.google.com/jsapi');
		$this->reports_data = $this->get('ReportData');
		
		parent::display($tpl);
	}

	protected function addToolbar() 
	{
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_OVERVIEW'), 'rsmembership');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';

		RSMembershipToolbarHelper::addToolbar('rsmembership');
	}
}