<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipController extends JControllerLegacy
{
	public function __construct() {
		parent::__construct();

		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsmembership/tables');

		$config   = RSMembershipConfig::getInstance();
		$version = (string) new RSMembershipVersion();

		// Load our CSS
		JHtml::_('stylesheet', 'com_rsmembership/rsmembership.css', array('relative' => true, 'version' => 'auto'));
		// Load our JS
		JHtml::_('script', 'com_rsmembership/rsmembership.js', array('relative' => true, 'version' => 'auto'));
		
		// Load Bootstrap on 3.x
		if ($config->get('load_bootstrap')) {
			JHtml::_('bootstrap.framework');
			JHtml::_('bootstrap.loadCss', true);
		}
	}
	
	// @desc Entry point for the subscription process.
	public function subscribe($new=true) {
		$app 			= JFactory::getApplication();
		$membership_id 	= $app->input->get('cid', 0, 'int');
		$extras			= $app->input->get('rsmembership_extra', array(), 'array');
		$model 			= $this->getModel('subscribe');
		
		// Empty the session everytime this page is accessed directly and not from within the controller
		if ($new) {
			$model->clearData();
		}
		
		// Try to bind the membership
		if (!$model->bindMembership($membership_id)) {
			$app->enqueueMessage($model->getError(), 'error');
			return $app->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		// Check if the user can subscribe to this membership
		if (!$model->canSubscribe()) {
			$app->enqueueMessage($model->getError(), 'error');
			return $app->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		// Check if it's out of stock.
		$membership = $model->getMembership();
		if ($membership->stock < 0) {
			$app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_OUT_OF_STOCK'), 'error');
			return $app->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		// Try to bind extras
		if ($extras) {
			$model->bindExtras($extras);
		}

		$view = $this->getView('subscribe', 'html');
		$view->setModel($model, true);
		$view->display();
	}

	public function setcoupon() {
		$app 	= JFactory::getApplication();
		$model 	= $this->getModel('subscribe');
		$membership_id 	= $app->input->get('membership_id', 0, 'int');
		$coupon 		= $app->input->get('coupon', '', 'string');

		$response = new stdClass();
		$response->status = true;
		$response->discount = 0;
		if (!$model->bindMembership($membership_id)) {
			$this->showResponse($response);
		}


		$coupon = $model->bindCoupon($coupon, true);
		if (is_bool($coupon)) {
			$this->showResponse($response);
		} else {
			$response->discount = $coupon->discount_price;
		}

		$this->showResponse($response);
	}

	protected function showResponse($data) {
		// Set proper document encoding
		JFactory::getDocument()->setMimeEncoding('application/json');

		// Echo the JSON encoded data.
		echo json_encode($data);

		// Close the application.
		JFactory::getApplication()->close();
	}
	
	// @desc Validation during subscription.
	public function validateSubscribe() {
		$app 	= JFactory::getApplication();
		$model 	= $this->getModel('subscribe');
		
		// Get needed data.
		$membership_id 	= $app->input->get('cid', 0, 'int');
		$extras			= $app->input->get('rsmembership_extra', array(), 'array');
		$coupon 		= $app->input->get('coupon', '', 'string');
		$data			= array(
			'username' 			=> $app->input->get('username', '', 'string'),
			'email' 			=> $app->input->get('email', '', 'string'),
			'name' 				=> $app->input->get('name', '', 'string'),
			'password'			=> $app->input->get('password',  '', 'raw'),
			'password2'			=> $app->input->get('password2', '', 'raw'),
			'fields'			=> $app->input->get('rsm_fields', array(), 'array'),
			'membership_fields'	=> $app->input->get('rsm_membership_fields', array(), 'array')
		);
		
		// Try to bind the membership
		if (!$model->bindMembership($membership_id)) {
			$app->enqueueMessage($model->getError(), 'error');
			return $app->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		// Try to bind extras
		if ($extras) {
			$model->bindExtras($extras);
		}

		// Store data in the session here, we're going to need it later on.
		$model->storeData(array(
			'id' 		=> $membership_id,
			'extras' 	=> $extras,
			'data'		=> $data,
			'coupon'	=> $coupon
		));
		
		// Check if the user can subscribe to this membership
		if ($data['email'] && ($userId = RSMembership::checkUser($data['email']))) {
			$user = JFactory::getUser($userId);

			if (!RSMembershipHelper::getConfig('allow_resubscribe')) {
				// Show some errors.
				$app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_EMAIL_NOT_OK'), 'error');

				// Redirect back.
				$app->redirect(JRoute::_('index.php?option=com_rsmembership&task=back&cid='.$membership_id, false));
			}

			if (!$model->canSubscribe($user)) {
				$app->enqueueMessage($model->getError(), 'error');

				// Redirect back.
				$app->redirect(JRoute::_('index.php?option=com_rsmembership&task=back&cid='.$membership_id, false));
			}
		}
		
		// Validate Captcha, bind data and check coupon code.
		if (!$model->validateCaptcha() || !$model->bindData($data) || !$model->bindCoupon($coupon)) {			
			// Show some errors.
			$app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_PLEASE_TYPE_FIELDS'), 'error');
			$app->enqueueMessage($model->getError(), 'error');
			
			// Redirect back.
			$app->redirect(JRoute::_('index.php?option=com_rsmembership&task=back&cid='.$membership_id, false));
		}

        // Check if terms and conditions have been accepted
        if ($model->getMembershipTerms() && !$app->input->getInt('i_agree_to_terms')) {
            // Show some errors.
            $app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_PLEASE_AGREE_MEMBERSHIP'), 'error');

            // Redirect back.
            $app->redirect(JRoute::_('index.php?option=com_rsmembership&task=back&cid='.$membership_id, false));
        }
		
		// Mark data as correct (to prevent people from accessing the next pages with invalid data).
		$model->markCorrectData($membership_id);
		
		// If one page checkout is enabled, just redirect to the payment gateway.
		if (RSMembershipHelper::getConfig('one_page_checkout')) {
			$app->input->set('payment', $app->input->get('payment', 'none', 'cmd'));
			return $this->paymentRedirect();
		} else {
			// Show the preview page.
			$view = $this->getView('subscribe', 'html');
			$view->setLayout('preview');
			$view->setModel($model, true);
			$view->display();
		}
	}
	
	public function paymentRedirect() {
		$payment = JFactory::getApplication()->input->get('payment', 'none', 'cmd');
		$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=payment&payment='.$payment, false));
	}
	
	public function payment() {
		$model 	= $this->getModel('subscribe');
		$app 	= JFactory::getApplication();
		
		// Set data from the session...
		if ($data = $model->getData()) {
			foreach ($data as $key => $value) {
				$app->input->set($key, $value);
			}
		}
		
		// Get needed data.
		$membership_id 	= $app->input->get('cid', 0, 'int');
		$extras			= $app->input->get('rsmembership_extra', array(), 'array');
		$coupon 		= $app->input->get('coupon', '', 'string');
		
		$username 	= $app->input->get('username', '', 'string');
		$username   = preg_replace('#[<>"\'%;()&\\\\]|\\.\\./#', '', $username);
		$data			= array(
			'username' 			=> $username,
			'email' 			=> $app->input->get('email', '', 'string'),
			'name' 				=> $app->input->get('name', '', 'string'),
			'password'			=> $app->input->get('password',  '', 'raw'),
			'password2'			=> $app->input->get('password2', '', 'raw'),
			'fields'			=> $app->input->get('rsm_fields', array(), 'array'),
			'membership_fields'	=> $app->input->get('rsm_membership_fields', array(), 'array')
		);
		$paymentPlugin 	= $app->input->get('payment', 'none', 'cmd');
		
		// Try to bind the membership
		if (!$model->bindMembership($membership_id)) {
			$app->enqueueMessage($model->getError(), 'error');
			return $app->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		// Try to bind extras
		if ($extras) {
			$model->bindExtras($extras);
		}
		
		if (!$model->bindData($data) || !$model->bindCoupon($coupon) || !$model->isCorrectData()) {
			// Show some errors.
			$app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_PLEASE_TYPE_FIELDS'), 'error');
			$app->enqueueMessage($model->getError(), 'error');
			
			// Redirect back.
			$app->redirect(JRoute::_('index.php?option=com_rsmembership&task=back&cid='.$membership_id, false));
		}
		
		$membership	 	= $model->getMembership();
		$transaction 	= $model->saveTransaction($paymentPlugin);
		$showPayments 	= $model->showPaymentOptions();
		if (!$showPayments) {
			$app->redirect(JRoute::_('index.php?option=com_rsmembership&task=thankyou', false));
		}
		
		// Show the payment page.
		$view = $this->getView('subscribe', 'html');
		$view->setLayout('payment');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function back() {
		$input 			= JFactory::getApplication()->input;
		$model 			= $this->getModel('subscribe');
		$membership_id 	= $input->get('cid', 0, 'int');
		
		// Set data back into the request
		if ($data = $model->getData()) {
			foreach ($data as $key => $value) {
				$input->set($key, $value);
			}
		}
		
		// Fallback for expired sessions
		if (empty($data) || empty($data['cid'])) {
			$input->set('cid', $membership_id);
		}
		
		$this->subscribe(false);
	}
	
	public function captcha() {
		$app   = JFactory::getApplication();
		$model = $this->getModel('subscribe');
		if ($model->getUseBuiltin()) {
			// Load Captcha
			if (!class_exists('JSecurImage')) {
				require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/securimage/securimage.php';
			}
			
			ob_end_clean();
			
			$captcha 				= new JSecurImage();
			$captcha->num_lines 	= RSMembershipHelper::getConfig('captcha_lines') ? 8 : 0;
			$captcha->code_length 	= RSMembershipHelper::getConfig('captcha_characters');
			$captcha->image_width 	= 30 * $captcha->code_length + 50;
			$captcha->show();
		}
		
		$app->close();
	}
	
	public function checkUsername() {
		$app			= JFactory::getApplication();
		$model 			= $this->getModel('subscribe');
		$suggestions 	= $model->checkUsername();
		
		echo implode('|', $suggestions);
		$app->close();
	}
	
	public function download() 
	{
		JFactory::getApplication()->input->set('view', 'mymembership');
		JFactory::getApplication()->input->set('layout', 'default');

		parent::display();
	}

	public function thankyou()
	{
		JFactory::getApplication()->input->set('view', 'thankyou');
		JFactory::getApplication()->input->set('layout', 'default');

		parent::display();
	}
	
	public function validateuser() 
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		$model = $this->getModel('user');
		if (!$model->_bindData())
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSMEMBERSHIP_PLEASE_TYPE_FIELDS'), 'warning');
			JFactory::getApplication()->input->set('view', 'user');
			JFactory::getApplication()->input->set('layout', 'default');

			parent::display();
		}
		else
		{
			$model->save();
			// Redirect
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=user', false), JText::_('COM_RSMEMBERSHIP_USER_SAVED'));
		}
	}

	public function cancel()
	{
		$model = $this->getModel('mymembership');
		$model->cancel();
		
		$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&cid='.$model->getCid(), false), JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_CANCELLED'));
	}
}