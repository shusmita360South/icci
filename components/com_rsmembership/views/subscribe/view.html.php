<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewSubscribe extends JViewLegacy
{
	public function display($tpl = null) {
		$app 		= JFactory::getApplication();
		$pathway 	= $app->getPathway();
		
		// Assign variables
		$this->membership = $this->get('Membership');
		$this->extras 	  = $this->get('Extras');
		$this->params 	  = clone($app->getParams('com_rsmembership'));
		$this->user 	  = JFactory::getUser();
		$this->logged	  = (bool) !$this->user->guest;
		$this->token 	  = JHtml::_('form.token');
		
		// Assign config variables
		$this->config			 = RSMembershipHelper::getConfig();
		$this->show_login 		 = $this->config->show_login;
		$this->choose_username 	 = $this->config->choose_username;
		$this->choose_password 	 = $this->config->choose_password;
		$this->currency 		 = $this->config->currency;
		$this->one_page_checkout = $this->config->one_page_checkout;
		$this->captcha_case_sensitive = $this->config->captcha_case_sensitive;
		$this->payments 		 = RSMembership::getPlugins(false);
		
		// Set pathway
		$pathway->addItem($this->membership->name, JRoute::_(RSMembershipRoute::Membership($this->membership->id, $app->input->getInt('Itemid'))));
		$pathway->addItem(JText::_('COM_RSMEMBERSHIP_SUBSCRIBE'), '');

		$model = $this->getModel();
		
		switch ($this->getLayout())
		{
			default:
				// Get the encoded return url
				$this->return 				= base64_encode(JUri::getInstance());
				$this->data 				= (object) $this->get('Data');
				$this->membershipterms 		= $this->get('MembershipTerms');
				$this->has_coupons 			= $this->get('HasCoupons');
				$this->fields 			 	= RSMembershipHelper::getFields();
				$this->membership_fields 	= RSMembershipHelper::getMembershipFields($this->membership->id);
				
				// Handle CAPTCHA
				$this->use_captcha 	 		= $this->get('UseCaptcha');
				$this->use_builtin 	 		= $this->get('UseBuiltin');
				$this->use_recaptcha 		= $this->get('UseReCaptcha');
				$this->use_recaptcha_new 	= $this->get('UseReCaptchaNew');

				// Start the init object
				$init = new stdClass();

				$doc = JFactory::getDocument();
				if ($this->use_builtin) {
					if ($doc->getType() == 'html') {
						$init->captcha_url = JRoute::_('index.php?option=com_rsmembership&task=captcha&sid=#SID#', false);
					}
				}

				if ($this->use_recaptcha) {
					if (!class_exists('JReCAPTCHA')) {
						require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/recaptcha/recaptchalib.php';
					}
						
					$this->show_recaptcha = JReCAPTCHA::getHTML($this->get('ReCaptchaError'));
				}
				
				if ($this->use_recaptcha_new) {
					if ($doc->getType() == 'html') {
						$doc->addScript('https://www.google.com/recaptcha/api.js?hl='.JFactory::getLanguage()->getTag());
					}
				}

				// Build the mandatory validate checks array
				$validate_checks = array();
				if (!empty($this->membershipterms)) {
					$validate_checks[] = 'membershipterms';
				}

				if ($this->choose_username && !$this->logged) {
					$validate_checks[] = 'username';
				}

				if ($this->choose_password && !$this->logged) {
					$validate_checks[] = 'password';
				}

				if (!$this->logged) {
					$validate_checks[] = 'name';
				}

				if (!empty($validate_checks)) {
					$init->validations = $validate_checks;
					if (in_array('username', $validate_checks)) {
						$init->check_user_url = JUri::root(true).'/index.php?option=com_rsmembership&task=checkusername';
					}
				}

				// Check for field validations
				$fields_validation 	= RSMembershipHelper::getFieldsValidation($this->membership->id);
				if (!empty($fields_validation)) {
					$init->field_validations = $fields_validation;
				}

				if (isset($init->validations) || isset($init->field_validations) || isset($init->captcha_url)) {
					// In case the scripts aren't loaded in the <head> area
					$inline_js = 'jQuery(function() {'."\n\t".'RSMembership.subscribe.init = jQuery.extend( {}, RSMembership.subscribe.init, '.json_encode($init).' );'."\n".'});'."\n";
					$doc->addScriptDeclaration($inline_js);
				}

				if ($this->one_page_checkout) {
					// display the grand total after payment selection
					RSMembershipHelper::buildGrandTotal();

					// the grand total must be without extras so that we can add it with javascript
					$current_total = $this->get('Total');
					$total_without_extras = $model->getTotal(true);

					// keep only the membership cost
					if ($current_total != $total_without_extras) {
						$this->grand_total = $current_total - $total_without_extras;
					} else {
						$this->grand_total = $current_total;
					}
				}

				$this->assignExtrasView();
			break;
			
			case 'preview':
				$this->fields 				= RSMembershipHelper::getFields(false);
				$this->membership_fields 	= RSMembershipHelper::getMembershipFields($this->membership->id, false);
				$this->data 				= (object) $this->get('Data');

				// display the grand total after payment selection
				RSMembershipHelper::buildGrandTotal();

			break;
			
			case 'payment':
				$this->html = $this->get('Html');
			break;
		}

		// Calculate the Total
		$this->total = $this->get('Total');
		
		// Do we need to display the payment options?
		$this->showPayments = $model->showPaymentOptions();

		parent::display();
	}
	
	protected function assignExtrasView() {
		// Create the View
		$view = new JViewLegacy(array(
			'name' 		=> 'extras',
			'base_path' => JPATH_SITE.'/components/com_rsmembership'
		));

		// Create the Model
		$model = JModelLegacy::getInstance('Extras', 'RSMembershipModel');
		
		// Assign the Model to the View and set it as default.
		$view->setModel($model, true);
		
		$view->model				= &$model;
		$view->item   				= $this->membership;
		$view->extras 				= $model->getItems();
		$view->show_subscribe_btn	= false;
		
		$this->extrasview = $view->loadTemplate();
	}
}