<?php
/**
 * @package    RSMembership!
 * @copyright  (c) 2009 - 2018 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die;

class RsmembershipControllerRemovedata extends JControllerForm
{
    public function request()
    {
        JSession::checkToken() or jexit('Invalid Token');

        try
        {
            $user = JFactory::getUser();
            if ($user->guest)
            {
                throw new Exception(JText::_('COM_RSMEMBERSHIP_MUST_BE_LOGGED_IN'));
            }

            if (!RSMembershipHelper::getConfig('allow_self_anonymisation', 0))
            {
                throw new Exception(JText::_('COM_RSMEMBERSHIP_THIS_FEATURE_MUST_BE_ENABLED'));
            }

            if ($user->authorise('core.admin'))
            {
                throw new Exception(JText::_('COM_RSMEMBERSHIP_THIS_FEATURE_IS_NOT_AVAILABLE_FOR_SUPER_USERS'));
            }

            // Get JConfig
            $config = JFactory::getConfig();

            // Create a token
            $token = JApplicationHelper::getHash(JUserHelper::genRandomPassword(10));
            $hashedToken = JUserHelper::hashPassword($token);

            // Save the token
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->qn('#__rsmembership_tokens'))
                ->where($db->qn('user_id') . ' = ' . $db->q($user->id));
            if ($db->setQuery($query)->loadObject())
            {
                $query->clear()
                    ->update($db->qn('#__rsmembership_tokens'))
                    ->set($db->qn('token') . ' = ' . $db->q($hashedToken))
                    ->where($db->qn('user_id') . ' = ' . $db->q($user->id));
            }
            else
            {
                $query->clear()
                    ->insert($db->qn('#__rsmembership_tokens'))
                    ->columns(array($db->qn('user_id'), $db->qn('token')))
                    ->values(implode(', ', array($db->q($user->id), $db->q($hashedToken))));
            }

            $db->setQuery($query)->execute();

            // Create the URL
            $uri 	= JUri::getInstance();
            $base	= $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
            $url    = $base . JRoute::_('index.php?option=com_rsmembership&task=removedata.process&token=' . $token, false);

            JFactory::getMailer()->sendMail($config->get('mailfrom'), $config->get('fromname'), $user->email, JText::sprintf('COM_RSMEMBERSHIP_REMOVE_REQUEST_EMAIL_SUBJECT', $user->username, $config->get('sitename')), JText::sprintf('COM_RSMEMBERSHIP_REMOVE_REQUEST_EMAIL_BODY', $user->name, $url), true);
        }
        catch (Exception $e)
        {
            jexit($e->getMessage());
        }

        jexit(JText::_('COM_RSMEMBERSHIP_LINK_HAS_BEEN_SENT'));
    }

    public function process()
    {
        $app    = JFactory::getApplication();
        $user   = JFactory::getUser();

        try
        {
            if ($user->guest)
            {
                $link = base64_encode((string) JUri::getInstance());
                $app->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $link, false), JText::_('COM_RSMEMBERSHIP_MUST_BE_LOGGED_IN'));
            }

            if (!RSMembershipHelper::getConfig('allow_self_anonymisation', 0))
            {
                throw new Exception(JText::_('COM_RSMEMBERSHIP_THIS_FEATURE_MUST_BE_ENABLED'));
            }

            if ($user->authorise('core.admin'))
            {
                throw new Exception(JText::_('COM_RSMEMBERSHIP_THIS_FEATURE_IS_NOT_AVAILABLE_FOR_SUPER_USERS'));
            }

            $token = $app->input->getCmd('token');
            if (!$token || strlen($token) != 32)
            {
                throw new Exception(JText::_('COM_RSMEMBERSHIP_TOKEN_IS_INCORRECT'));
            }

            // Let's see if the token matches
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->qn('token'))
                ->from($db->qn('#__rsmembership_tokens'))
                ->where($db->qn('user_id') . ' = ' . $db->q($user->id));
            $dbToken = $db->setQuery($query)->loadResult();

            if (!$dbToken || !JUserHelper::verifyPassword($token, $dbToken))
            {
                throw new Exception(JText::_('COM_RSMEMBERSHIP_TOKEN_IS_INCORRECT'));
            }

            // Delete the token
            $query->clear()
                ->delete($db->qn('#__rsmembership_tokens'))
                ->where($db->qn('user_id') . ' = ' . $db->q($user->id));
            $db->setQuery($query)->execute();

            // Anonymise data
            RSMembershipHelper::anonymise($user->id);

            $app->logout();
            $app->redirect(JRoute::_('index.php?option=com_rsmembership&view=removedata', false));
        }
        catch (Exception $e)
        {
            $app->enqueueMessage($e->getMessage(), 'error');
			$this->setRedirect(JRoute::_('index.php', false));
        }
    }
}