<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewReports extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->addToolbar();
		$this->document->addScript('https://www.google.com/jsapi');

		$this->form  	 = $this->get('Form');
		$this->fieldsets = $this->form->getFieldsets();
		$this->field 	 = $this->get('RSFieldset');
		$this->accordion = $this->get('RSAccordion');

		parent::display($tpl);
	}

	protected function addToolbar() 
	{
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_REPORTS'),'reports');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('reports');
		JToolBarHelper::back(JText::_('COM_RSMEMBERSHIP_BACK_TO_OVERVIEW'), "index.php?option=com_rsmembership");
	}
}