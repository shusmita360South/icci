<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelThankYou extends JModelLegacy
{
	public $message;
	
	public function __construct()
	{
		parent::__construct();

		$app 	 = JFactory::getApplication();
		$option  = 'com_rsmembership';

		$session  = JFactory::getSession();
		$action   = $session->get($option.'.subscribe.action', null);		
		$message  = $session->get($option.'.subscribe.thankyou', null);
		$redirect = $session->get($option.'.subscribe.redirect', null);

		$session->set($option.'.subscribe.action', null);
		$session->set($option.'.subscribe.thankyou', null);
		$session->set($option.'.subscribe.redirect', null);
		
		// No session data
		if (is_null($action)) {
			$app->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		// Redirect?
		if ($action == 1 && $redirect) {
			$app->redirect($redirect);
		}
		
		// Store the message
		$this->message = $message;
	}
	
	public function getMessage()
	{		
		return $this->message;
	}
}