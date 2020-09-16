<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('BannerHelper', JPATH_ROOT . '/components/com_banners/helpers/banner.php');
?>


<div class="mod_banners mod_partner generalpages-detail section-padding-tb center <?php echo $moduleclass_sfx; ?> ">
		<div class="grid-container layout4">
			<h2><?php echo $module->title; ?></h2>
			<?php if($headerText):?>
				<p class="intro"><?php echo $headerText?></p>
			<?php endif; ?>


			<div uk-grid class="layout4-grid uk-child-width-auto">
		    <?php foreach ($list as $key => $item) :?>
		    	<?php $link = JRoute::_('index.php?option=com_banners&task=click&id=' . $item->id); ?>
					
						<?php $imageurl = $item->params->get('imageurl'); ?>
		      
		        <div class="uk-width-1-1 uk-width-1-5@s">
		          <div class="content center">
		              <img class="" src="<?php echo $imageurl?>" alt="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>"/>
		              
		              <p><?php echo $item->description;?></p>
		              <a class="readmore" href="<?php echo $link; ?>" target="_blank">Discover More</a>
		          </div>
		        </div>
		    <?php endforeach;?>

		</div>
			
		</div>

</div>
