<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerConfiguration extends JControllerLegacy
{
	public function __construct() {
		parent::__construct();
		
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin', 'com_rsmembership')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		$this->registerTask('apply', 'save');
	}

	/**
	 * Logic to save configuration
	*/
	public function save() 
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$data  = JFactory::getApplication()->input->get('jform', array(), 'array');
		$model = $this->getModel('configuration');
		$form  = $model->getForm();

		// Validate the posted data.
		$return = $model->validate($form, $data);

		// Check for validation errors.
		if ($return === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					JFactory::getApplication()->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					JFactory::getApplication()->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=configuration', false));
			return false;
		}

		$data = $return;

		if (!$model->save($data)) 
			$this->setMessage($model->getError(), 'error');
		else
			$this->setMessage(JText::_('COM_RSMEMBERSHIP_CONFIGURATION_OK'));


		$task = $this->getTask();
		if ($task == 'apply') {
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=configuration', false));
		} elseif ($task == 'save') {
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
	}

	public function idevCheckConnection()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit( 'Invalid Token' );

		// Get the model
		$model = $this->getModel('Configuration', 'RSMembershipModel');
		
		// Save
		$result = $model->idevCheckConnection();
		
		$tabposition = JFactory::getApplication()->input->get('tabposition', 0, 'int');

		$link = 'index.php?option=com_rsmembership&view=configuration&tabposition='.$tabposition;
		
		$msg = '';
		if ($result)
			$msg = JText::_('COM_RSMEMBERSHIP_IDEV_CONNECT_SUCCESS');
		
		// Redirect
		$this->setRedirect($link, $msg);
	}
}