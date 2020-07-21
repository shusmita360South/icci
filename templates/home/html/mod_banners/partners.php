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


<div class="mod_banners mod_partner section-padding-tb bg-white center">
		<div class="grid-container">
			<h2><?php echo $module->title; ?></h2>
			<?php if($headerText):?>
				<p><?php echo $headerText?></p>
			<?php endif; ?>
		
			<div class="banneritem-grid uk-margin-medium-top center">
				<div uk-grid class=" uk-flex-center">
				<?php foreach ($list as $item) : ?>
					<div class="banneritem uk-width-1-3 uk-width-1-3@s uk-width-1-6@m">
						<?php $link = JRoute::_('index.php?option=com_banners&task=click&id=' . $item->id); ?>
					
						<?php $imageurl = $item->params->get('imageurl'); ?>
						<?php $width = $item->params->get('width'); ?>
						<?php $height = $item->params->get('height'); ?>

							<?php // Image based banner ?>
							<?php $baseurl = strpos($imageurl, 'http') === 0 ? '' : JUri::base(); ?>
							<?php $alt = $item->params->get('alt'); ?>
							<?php $alt = $alt ?: $item->name; ?>
							<?php $alt = $alt ?: JText::_('MOD_BANNERS_BANNER'); ?>
							<?php if ($item->clickurl) : ?>
								<?php // Wrap the banner in a link ?>	
									<?php // Open in a new window ?>
									<a
										href="<?php echo $link; ?>" target="_blank" rel="noopener noreferrer"
										title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>">
										<img
											src="<?php echo $baseurl . $imageurl; ?>"
											alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
											<?php if (!empty($width)) echo ' width="' . $width . '"';?>
											<?php if (!empty($height)) echo ' height="' . $height . '"';?>
										/>
									</a>
							<?php else : ?>
								<?php // Just display the image if no link specified ?>
								<img
									src="<?php echo $baseurl . $imageurl; ?>"
									alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
									<?php if (!empty($width)) echo ' width="' . $width . '"';?>
									<?php if (!empty($height)) echo ' height="' . $height . '"';?>
								/>
							<?php endif; ?>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
		</div>

</div>
