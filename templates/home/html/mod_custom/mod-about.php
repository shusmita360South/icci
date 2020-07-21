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


<div class="mod-custom mod-about section-padding-tb" id="about" >
	<div class="grid-container-small-small">
		<h5 class="mod-subtitle orange" uk-scrollspy="cls: mw-animation-slide-left; repeat: false"><?php echo $module->title; ?></h5>
		<?php echo $module->content; ?>
	</div>

</div>
