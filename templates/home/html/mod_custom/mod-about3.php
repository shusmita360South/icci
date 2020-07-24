<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>


<div class="mod-custom mod-about-3 section-padding-tb">
	<div class="grid-container-small">
		<div uk-grid>
			<div class="uk-width-1-1 uk-width-1-2@s">
				<div class="content">
					<?php echo $module->content; ?>
				</div>
			</div>
			<div class="uk-width-1-1 uk-width-1-2@s">
				<img src="<?php echo $params->get('backgroundimage'); ?>"/>
			</div>
		</div>
	</div>

</div>
