<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/adapter.php';
require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/rsmembership.php';
require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/version.php';
require_once JPATH_SITE.'/components/com_rsmembership/helpers/route.php';
require_once JPATH_COMPONENT.'/controller.php';

$controller	= JControllerLegacy::getInstance('RSMembership');
$app 		= JFactory::getApplication();
$task 		= $app->input->get('task');

$controller->execute($task);
$controller->redirect();