<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSMembershipViewMembership extends JViewLegacy
{
	protected $form;
	protected $item;

	public function display($tpl = null) 
	{
		// fields
		$this->field 	 = $this->get('RSFieldset');

		// tabs
		$this->tabs	 	 = $this->get('RSTabs');

		// accordion
		$this->accordion_user 	= $this->get('RSAccordion');
		$this->accordion_admin  = $this->get('RSAccordion');

		// get membership xml form
		$this->form  	 = $this->get('Form');

		// get fieldsets -> used to get the label
		$this->fieldsets = $this->form->getFieldsets();

		// get membership data
		$this->item   				 = $this->get('Item');
		$this->attachments 			 = $this->get('attachments');
		$this->attachmentsPagination = $this->get('attachmentsPagination');
		$this->sharedPagination 	 = $this->get('sharedPagination');
		$this->app					 = JFactory::getApplication();
		$this->ordering 	 		 = $this->get('SharedOrdering');


		$jinput = JFactory::getApplication()->input;
		$this->activeTab =  $jinput->get('tabposition', 0, 'int');

		$this->addToolbar();
		
		parent::display($tpl);
	}

	protected function getPlaceholders($type) {
		$placeholders = array();
		
		switch ($type) {
			case 'thankyou':
				$placeholders = array(
					'{membership}', '{category}', '{extras}', '{email}', '{name}', '{username}', '{continue}', '{price}', '{coupon}', '{payment}', '{transaction_id}'
				);
			break;
			
			case 'admin_email_new':
			case 'admin_email_renew':
			case 'admin_email_addextra':
			case 'user_email_new':
			case 'user_email_renew':
			case 'user_email_addextra':
				$placeholders = array(
					'{membership}', '{category}', '{extras}', '{email}', '{name}', '{username}', '{price}', '{coupon}', '{payment}', '{transaction_id}'
				);
			break;
			
			case 'admin_email_upgrade':
			case 'user_email_upgrade':
				$placeholders = array(
					'{membership}', '{category}', '{membership_from}', '{extras}', '{email}', '{name}', '{username}', '{price}', '{coupon}', '{payment}', '{transaction_id}'
				);
			break;
			
			case 'admin_email_expire':
			case 'user_email_expire':
				$placeholders = array(
					'{membership}', '{membership_id}', '{category}', '{membership_end}', '{extras}', '{email}', '{name}', '{username}', '{interval}', '{price}', '{subscription_id}'
				);
			break;
			
			case 'admin_email_denied':
			case 'user_email_denied':
				$placeholders = array(
					'{membership}', '{extras}', '{category}', '{email}', '{username}', '{name}', '{price}', '{coupon}', '{payment}', '{transaction_id}'
				);
			break;
			
			case 'admin_email_approved':
			case 'user_email_approved':
				$placeholders = array(
					'{membership}', '{category}', '{price}', '{extras}', '{email}', '{username}', '{name}', '{membership_start}', '{membership_end}', '{transaction_id}', '{transaction_hash}'
				);
				if ($type == 'user_email_approved' && isset($this->item) && !empty($this->item->use_membership_invoice))
				{
					$placeholders[] = '{invoice}';
				}
			break;

			case 'invoice':
				$placeholders = array(
					'{membership}', '{category}', '{extras}', '{email}', '{name}', '{username}', '{total_price}', '{coupon}', '{discount_type}', '{discount_value}', '{payment}', '{transaction_id}', '{transaction_hash}', '{membership_from}', '{invoice_id}', '{tax_type}', '{tax_value}', '{invoice_transaction_table}', '{date_purchased}', '{site_name}', '{site_url}'
				);
			break;
		}
		
		// Get cached custom fields
		static $fields = array();
		static $membership_fields = array();
		if (!$fields) {
			$fields = RSMembership::getCustomFields();
		}
		if (!$membership_fields) {
			$membership_fields  = RSMembership::getCustomMembershipFields($this->item->id);
		}
		
		// Add custom fields
		foreach ($fields as $field) {
			$placeholders[] = '{'.$field->name.'}';
		}
		
		// Add membership fields
		foreach ($membership_fields as $membership_field) {
			$placeholders[] = '{'.$membership_field->name.'}';
		}
		
		return implode(', ', $placeholders);
	}

	protected function addToolbar() 
	{
		$id	= JFactory::getApplication()->input->get('id', 0, 'int');
		if ($id) 
			JToolBarHelper::title(JText::sprintf('COM_RSMEMBERSHIP_EDIT_MEMBERSHIP', $this->escape($this->item->name)), 'memberships');
		else 
			JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_NEW_MEMBERSHIP'), 'memberships');

		JToolBarHelper::apply('membership.apply');
		JToolBarHelper::save('membership.save');
		JToolBarHelper::save2new('membership.save2new');
		JToolBarHelper::deleteList(JText::_('COM_RSMEMBERSHIP_CONFIRM_DELETE'), 'membership.foldersRemove', JText::_('JTOOLBAR_DELETE'));
		JToolBarHelper::publishList('membership.folderspublish', JText::_('JTOOLBAR_PUBLISH'));
		JToolBarHelper::unpublishList('membership.foldersunpublish', JText::_('JTOOLBAR_UNPUBLISH'));
		JToolBarHelper::cancel('membership.cancel');
	}
}