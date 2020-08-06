<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSMembershipViewMembership_Subscriber extends JViewLegacy
{
	protected $form;
	protected $item;

	public function display($tpl = null) 
	{
		$this->addToolbar();
		
		// fields
		$this->field	 = $this->get('RSFieldset');

		// get subcribers's membership xml form
		$this->form  = $this->get('Form');

		// get fieldsets -> used to get the label
		$this->fieldsets = $this->form->getFieldsets();
		
		// get subscriber's membership data
		$this->item  = $this->get('Item');
		
		// get prices
		$this->prices = $this->get('Prices');
		
		// get tabs
		$this->tabs	 = $this->get('RSTabs');
		
		parent::display($tpl);
	}

	protected function addToolbar() {
		JToolBarHelper::save('membership_subscriber.save');
		JToolBarHelper::cancel('membership_subscriber.cancel');
	}
	
	protected function jsEscape($string) {
		return addcslashes($string, "'");
	}
}