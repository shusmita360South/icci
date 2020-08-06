<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSMembershipViewCategory extends JViewLegacy
{
	protected $form;
	protected $item;

	public function display($tpl = null) 
	{
		// fields
		$this->field	 = $this->get('RSFieldset');

		// get category xml form
		$this->form  = $this->get('Form');

		// get fieldsets -> used to get the label
		$this->fieldsets = $this->form->getFieldsets();
		
		// get category
		$this->item  = $this->get('Item');

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() 
	{
		$id		= JFactory::getApplication()->input->get('id', 0, 'int');
		if ($id) 
			JToolBarHelper::title(JText::sprintf( 'COM_RSMEMBERSHIP_CATEGORY_EDIT', $this->escape($this->item->name) ), 'categories');
		else 
			JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_CATEGORY_NEW'), 'categories');

		JToolBarHelper::apply('category.apply');
		JToolBarHelper::save('category.save');
		JToolBarHelper::save2new('category.save2new');
		JToolBarHelper::cancel('category.cancel');
	}
}