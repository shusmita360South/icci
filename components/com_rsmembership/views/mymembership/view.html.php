<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewMymembership extends JViewLegacy
{
	public function display($tpl = null)
	{
		$app 	 = JFactory::getApplication();
		// Set params
		$this->params = clone($app->getParams('com_rsmembership'));

		$display = null;
		$pathway_item  = JText::_('COM_RSMEMBERSHIP_MEMBERSHIP');
		if ($terms = $this->get('terms')) {
			$this->terms  = $terms;
			$this->action = $this->escape(JRoute::_(JUri::getInstance(),false));

			$display = 'terms';
		} else {
			// Get the layout
			$layout = $this->getLayout();
			$model = $this->getModel();
			if ($layout == 'addextra') {

				$this->extra_id 	 = $this->get('extraId');
				$membership_id 		 = $this->get('cid');

				// store in session the ids
				$model->bindId($membership_id, $this->extra_id);

				// check if extra is already purchased (redirects if it is)
				$model->checkBoughtExtra();

				$this->payments = RSMembership::getPlugins(false);
				// get the encoded return url
				$this->return 	= base64_encode(JUri::getInstance());
				$this->data 	= $this->get('subscriberData');

				// get the membership
				$this->membership 		 = $model->getMembershipSubscriber();
				// get the user
				$this->user		         = JFactory::getUser();
				// Get the terms
                $this->membershipterms   = $this->get('membershipterms');

				$this->fields 	 		 = RSMembershipHelper::getFields(false);
				$this->membership_fields = RSMembershipHelper::getMembershipFields($this->membership->id, false, $this->user->id, true, $this->membership->last_transaction_id);

				// get the extra
				$this->extra 	= $model->getExtra($this->extra_id);
				$this->cid 	 	= $membership_id;
				$this->token	= JHtml::_('form.token');

				$pathway_item = JText::_('COM_RSMEMBERSHIP_ADDEXTRA');

			} else if($layout == 'upgrade') {

				$from_id 	 = $this->get('cid');
				$to_id 		 = $this->get('toid');

				// store in session the membership_id
				$model->bindId($from_id, $to_id, 'upgrade');

				$this->payments = RSMembership::getPlugins(false);

				// get the encoded return url
				$this->return 	= base64_encode(JUri::getInstance());

				$this->data 	= $this->get('subscriberData');

				// get the upgrade
				$this->upgrade = $model->getMembershipSubscriber('upgrade');
				// get the user
				$this->user		= JFactory::getUser();

				// price
				$this->total 	= RSMembershipHelper::getPriceFormat($this->upgrade->price);

				$this->fields	= RSMembershipHelper::getFields(true);
				$this->membership_fields = RSMembershipHelper::getMembershipFields($this->upgrade->membership_to_id, true, $this->user->id, true);

				$this->membershipterms = $this->get('membershipterms');

				// Start the init object
				$init = new stdClass();

				// Build the mandatory validate checks array
				if (!empty($this->membershipterms)) {
					$init->validations = array('membershipterms');
				}

				// Check for field validations
				$fields_validation 	= RSMembershipHelper::getFieldsValidation($this->upgrade->membership_to_id);
				if (!empty($fields_validation)) {
					$init->field_validations = $fields_validation;
				}

				if (isset($init->validations) || isset($init->field_validations)) {
					// In case the scripts aren't loaded in the <head> area
					$inline_js = 'jQuery(function() {'."\n\t".'RSMembership.subscribe.init = jQuery.extend( {}, RSMembership.subscribe.init, '.json_encode($init).' );'."\n".'});'."\n";
					JFactory::getDocument()->addScriptDeclaration($inline_js);
				}


				$this->config 	= RSMembershipHelper::getConfig();
				$this->cid 		= $from_id;
				$this->token	= JHtml::_('form.token');

				$pathway_item = JText::_('COM_RSMEMBERSHIP_UPGRADE');

			} else if($layout == 'renew'){

				$membership_id 		 = $this->get('cid');
				// store in session the membership_id
				$model->bindId($membership_id, null, 'renew');

				$this->payments = RSMembership::getPlugins(false);

				// get the encoded return url
				$this->return 	= base64_encode(JUri::getInstance());

				$this->data = $this->get('subscriberData');

				// get the user
				$this->user		= JFactory::getUser();

				// get the membership
				$this->membership 		 = $model->getMembershipSubscriber('renew');
				$this->membershipterms 	 = $this->get('membershipterms');
				$this->fields 			 = RSMembershipHelper::getFields(true);
				$this->membership_fields = RSMembershipHelper::getMembershipFields($this->membership->id, true, $this->user->id, true, $this->membership->last_transaction_id);

				// Start the init object
				$init = new stdClass();

				// Build the mandatory validate checks array
				if (!empty($this->membershipterms)) {
					$init->validations = array('membershipterms');
				}

				// Check for field validations
				$fields_validation 	= RSMembershipHelper::getFieldsValidation($this->membership->id);
				if (!empty($fields_validation)) {
					$init->field_validations = $fields_validation;
				}

				if (isset($init->validations) || isset($init->field_validations)) {
					// In case the scripts aren't loaded in the <head> area
					$inline_js = 'jQuery(function() {'."\n\t".'RSMembership.subscribe.init = jQuery.extend( {}, RSMembership.subscribe.init, '.json_encode($init).' );'."\n".'});'."\n";
					JFactory::getDocument()->addScriptDeclaration($inline_js);
				}

				$this->extras = $this->get('boughtExtrasRenew');
				$this->cid 		= $this->get('cid');
				$this->config	= RSMembershipHelper::getConfig();
				$this->token	= JHtml::_('form.token');

				$pathway_item = JText::_('COM_RSMEMBERSHIP_RENEW');

			} else if($layout == 'payment') {

				$action_type = $app->input->getCmd('action_type', 'addextra');

				switch($action_type) {
					case 'addextra':
						// process the payment
						$model->addExtraPayment();

						$pathway_item 			 = JText::_('COM_RSMEMBERSHIP_ADDEXTRA');
						$this->html_container_id = 'rsm_addextra_container';
					break;

					case 'renew':
						// process the payment
						$model->renewPayment();

						$pathway_item 			 = JText::_('COM_RSMEMBERSHIP_RENEW');
						$this->html_container_id = 'rsm_renew_payment_content';
					break;

					case 'upgrade':
						// process the payment
						$model->upgradePayment();

						$pathway_item 			 = JText::_('COM_RSMEMBERSHIP_RENEW');
						$this->html_container_id = 'rsm_renew_payment_content';
					break;
				}
				// return the output
				$this->html = $this->get('html');

			} else {

				$this->cid = $this->get('cid');
				$this->membership = $this->get('membership');
				$this->membershipterms = $this->get('membershipterms');
				$this->boughtextras = $this->get('boughtextras');
				$this->extras = $this->get('extras');
				$upgrades_array = $this->get('upgrades');

				$upgrades = array();
				foreach ($upgrades_array as $upgrade)
					$upgrades[] = JHtml::_('select.option', $upgrade->membership_to_id, $upgrade->name . ' - ' . RSMembershipHelper::getPriceFormat($upgrade->price));

				$has_upgrades = !empty($upgrades);
				$this->has_upgrades = $has_upgrades;

				$lists['upgrades'] = JHtml::_('select.genericlist', $upgrades, 'to_id', 'class="inputbox input-medium"');

				$this->folders = $this->get('folders');
				$this->files = $this->get('files');
				$this->previous = $this->get('previous');
				$this->from = $this->get('from');
				$this->lists = $lists;

				$Itemid = $app->input->get('Itemid', 0, 'int');
				$this->Itemid = '';
				if ($Itemid > 0)
					$this->Itemid = '&Itemid=' . $Itemid;

				// get the logged user
				$this->user = JFactory::getUser();
				$this->membership_fields = RSMembershipHelper::getMembershipFields($this->membership->membership_id, false, $this->user->id, true, $this->membership->last_transaction_id);

			}

			$this->currency = RSMembershipHelper::getConfig('currency');

			if (in_array($layout,array('addextra', 'upgrade', 'renew'))) {
				// display the grand total after payment selection
				RSMembershipHelper::buildGrandTotal();
			}
		}

		$pathway = $app->getPathway();

		// Set pathway
		$pathway->addItem($pathway_item, '');

        if (!empty($this->membership))
        {
            $this->params->set('page_heading', $this->membership->name);
        }

		parent::display($display);
	}
}