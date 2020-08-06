<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerSubscriber extends JControllerForm
{
	public function __construct() {
		parent::__construct();
	}

	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'user_id')
	{
		$append    = parent::getRedirectToItemAppend($recordId, $urlVar);
		$user_id   = JFactory::getApplication()->input->get('id', 0,'int');

		if ($user_id) 
			$append .= '&user_id=' . $user_id;

		$model = $this->getModel();
		if ($temp_id = $model->getTempId()) {
			$append .= '&temp_id=' . $temp_id;
		}

		return $append;
	}

	public function cancel($key = null) 
	{
		parent::cancel($key);
		
		$model = $this->getModel();
		if ($model->getTempId()) {
			$this->setRedirect( JRoute::_('index.php?option=com_rsmembership&view=transactions', false) );
		}
	}

	public function anonymise()
    {
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $id = $this->input->getInt('id');

        try
        {
            $model = $this->getModel('subscriber');
            $model->anonymise($id);

            JFactory::getApplication()->enqueueMessage(JText::_('COM_RSMEMBERSHIP_DATA_HAS_BEEN_ANONYMISED'), 'success');
        }
        catch (Exception $e)
        {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        $this->setRedirect( JRoute::_('index.php?option=com_rsmembership&task=subscriber.edit&user_id=' . $id, false) );
    }
}