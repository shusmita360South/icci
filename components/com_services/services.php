<?php
# no direct access
defined('_JEXEC') or die;

# Include dependancies
jimport('joomla.application.component.controller');

# include helper files
require_once JPATH_COMPONENT.'/helpers/helper.php';

# Execute the task.
$controller	= JControllerLegacy::getInstance('Services');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
