<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();
if (!$user->authorise('core.manage', 'com_rsmembership')) {
	return JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
}
require_once JPATH_COMPONENT.'/helpers/adapter.php';
require_once JPATH_COMPONENT.'/helpers/rsmembership.php';
require_once JPATH_COMPONENT.'/helpers/version.php';
require_once JPATH_COMPONENT.'/helpers/patches.php';

// Require the base controller
require_once JPATH_COMPONENT.'/controller.php';

JHtml::_('behavior.framework');

RSMembershipHelper::buildHead();

$controller	= JControllerLegacy::getInstance('RSMembership');
$task 		= JFactory::getApplication()->input->get('task');
$controller->execute($task);
$controller->redirect();