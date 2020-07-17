<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.tooltip');

// Add Stylesheets
$doc->addStyleSheet('templates/' .$this->template. '/css/template.css');

// If Right-to-Left
if ($this->direction == 'rtl') :
	$doc->addStyleSheet('../media/jui/css/bootstrap-rtl.css');
endif;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

// Check if debug is on
$config = JFactory::getConfig();
$debug  = (boolean) $config->get('debug');

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="'. JURI::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';
}
else if ($this->params->get('theme') == "flat")
{
	$logo = '<img src="'. JURI::root() .'administrator/templates/' .$this->template. '/images/logo-flat.png" alt="'. $sitename .'" />';
}
else if ($this->params->get('theme') == "mac" || $this->params->get('theme') == "carbon" || $this->params->get('theme') == "sepia" || $this->params->get('theme') == "ironman" || $this->params->get('theme') == "masterchief")
{
	$logo = '<img src="'. JURI::root() .'administrator/templates/' .$this->template. '/images/logo-inverse.png" alt="'. $sitename .'" />';
}
else
{
	$logo = '<img src="'. JURI::root() .'administrator/templates/' .$this->template. '/images/logo.png" alt="'. $sitename .'" />';
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<jdoc:include type="head" />
	<script type="text/javascript">
	/*
		window.addEvent('domready', function () {
			document.getElementById('form-login').username.select();
			document.getElementById('form-login').username.focus();
		});
	*/
	</script>
	<!--[if lt IE 9]>
		<script src="../media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="<?php echo $this->params->get('theme', 'mac'); ?> site <?php echo $option . " view-" . $view . " layout-" . $layout . " task-" . $task . " itemid-" . $itemid . " ";?>">
	<!-- Container -->
	<div class="container-fixed">
		<div id="content">
			<!-- Begin Content -->
			<div id="login-form" class="login">
				<p class="center">
					<?php echo $logo;?>
				</p>
				<jdoc:include type="message" />
				<jdoc:include type="component" />
			</div>
			<noscript>
				<?php echo JText::_('JGLOBAL_WARNJAVASCRIPT') ?>
			</noscript>
			<!-- End Content -->
		</div>
	</div>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
