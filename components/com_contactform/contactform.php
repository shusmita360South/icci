<?php
/**
 * @version     1.0.0
 * @package     com_contactform
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      360South Pty Ltd <tech@360south.com.au> - http://www.360south.com.au/
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

# include the helper files
require_once JPATH_COMPONENT.'/helpers/helper.php';

// Execute the task.
$controller	= JControllerLegacy::getInstance('Contactform');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
