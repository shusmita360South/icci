<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSMembershipViewExtraValue extends JViewLegacy
{
	protected $form;
	protected $item;

	public function display($tpl = null) 
	{
		// fields
		$this->field = $this->get('RSFieldset');
		// fields
		$this->tabs	 = $this->get('RSTabs');
		// get ExtraValue xml form
		$this->form  = $this->get('Form');

		// get fieldsets -> used to get the label
		$this->fieldsets = $this->form->getFieldsets();

		// get ExtraValue
		$this->item  = $this->get('Item');

		// get Shared Content Ordering
		$this->ordering  = $this->get('SharedOrdering');
		
		$this->pagination = $this->get('sharedPagination');

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar() 
	{
		$id	= JFactory::getApplication()->input->get('id', 0, 'int');

		if ($id) 
			JToolBarHelper::title(JText::sprintf('COM_RSMEMBERSHIP_EDIT_EXTRA_VALUE', $this->escape($this->item->name)), 'extras');
		else 
			JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_NEW_EXTRA_VALUE'), 'extras');

		JToolBarHelper::apply('extravalue.apply');
		JToolBarHelper::save('extravalue.save');
		JToolBarHelper::save2new('extravalue.save2new');
		JToolBarHelper::deleteList(JText::_('COM_RSMEMBERSHIP_CONFIRM_DELETE'), 'extravalue.foldersRemove', JText::_('JTOOLBAR_DELETE'));
		JToolBarHelper::publishList('extravalue.folderspublish', JText::_('JTOOLBAR_PUBLISH'));
		JToolBarHelper::unpublishList('extravalue.foldersunpublish', JText::_('JTOOLBAR_UNPUBLISH'));
		JToolBarHelper::cancel('extravalue.cancel');
	}
}