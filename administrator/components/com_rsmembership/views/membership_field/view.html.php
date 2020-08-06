<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSMembershipViewMembership_Field extends JViewLegacy
{
	protected $form;
	protected $item;

	public function display($tpl = null) 
	{
		// fields
		$this->field	 = $this->get('RSFieldset');

		// get field xml form
		$this->form  = $this->get('Form');

		// get fieldsets -> used to get the label
		$this->fieldsets = $this->form->getFieldsets();
		
		// get field data
		$this->item  = $this->get('Item');

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() 
	{
		$id		= JFactory::getApplication()->input->get('id', 0, 'int');

		if ($id) 
			JToolBarHelper::title(JText::sprintf('COM_RSMEMBERSHIP_EDIT_FIELD',$this->item->name), 'membership_fields');
		else 
			JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_NEW_FIELD'), 'membership_fields');

		JToolBarHelper::apply('membership_field.apply');
		JToolBarHelper::save('membership_field.save');
		JToolBarHelper::save2new('membership_field.save2new');
		JToolBarHelper::cancel('membership_field.cancel');
	}
}