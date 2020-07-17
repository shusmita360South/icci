<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen');

$document = JFactory::getDocument();
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="form-login" class="form-vertical">
	<fieldset class="loginform row-fluid">
		<div class="control-group">
			<div class="controls">
			<label for="mod-login-username" class="element-invisible"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label><input name="username" tabindex="1" id="mod-login-username" type="text" class="span12" placeholder="<?php echo JText::_('JGLOBAL_USERNAME'); ?>" size="15" />
			<label for="mod-login-password" class="element-invisible"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label><input name="passwd" tabindex="2" id="mod-login-password" type="password" class="span12"  placeholder="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>" size="15" />
		</div>
		<div class="control-group">
			<div class="controls">
				<div class="btn-group pull-left">
					<button tabindex="3" class="btn btn-primary btn-large"><i class="icon-lock icon-white"></i> <?php echo JText::_('MOD_LOGIN_LOGIN'); ?></button>
				</div>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
					<label for="lang" class="element-invisible"><?php echo JText::_('MOD_LOGIN_LANGUAGE'); ?></label><?php echo $langs; ?>
				</div>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<p>
					<a href="<?php echo JURI::root()?>index.php?option=com_users&view=remind"><?php echo JText::_('MOD_LOGIN_REMIND'); ?></a>
				</p>
				<p>
					<a href="<?php echo JURI::root()?>index.php?option=com_users&view=reset"><?php echo JText::_('MOD_LOGIN_RESET'); ?></a>
				</p>
			</div>
		</div>
		<input type="hidden" name="option" value="com_login" />
		<input type="hidden" name="task" value="login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</form>
