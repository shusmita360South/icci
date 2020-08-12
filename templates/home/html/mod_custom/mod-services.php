<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_BASE . '/templates/home/assets/helper.php';
$helper = new TempHelper();
$list = $helper->getServices();
$itemid = 104;
?>


<div class="mod-custom mod-services light-bg section-padding-tb" >
	<div class="grid-container-medium">
    <div class="top">
      <h2><?php echo $module->title; ?></h2>
	  	<h5><?php echo $module->content; ?></h5>
    </div>
		
		  
		<div uk-grid class="bottom uk-margin-large-top">
		 	<?php foreach ($list as $item): ?>
        
        <?php
          $linky = JURI::base().substr(JRoute::_('index.php?option=com_services&view=details&id='.$item->id.':'.JFilterOutput::stringURLSafe($item->title).'&Itemid='.$itemid),strlen(JURI::base(true))+1);
       
        ?>
      
        <div class="uk-width-1-1 uk-width-1-3@s">
            <a href="<?php echo $linky;?>">
              <div class="item">
                  <div class="image">
                    <img src="<?php echo $item->image;?>"/>     
                  </div>
                  <div class="content uk-margin-medium-top">
                  
                      <h4><?php echo $item->title; ?></h4>
                      <p><?php echo $item->lintro; ?></p>
                      <p class="readmore">Read More</p>
                  </div>
  
              </div>
            </a>
        </div>
      <?php endforeach; ?>
    </div>

	</div>
	
</div>

