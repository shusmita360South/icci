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
$list = $helper->getMemberlevels();
$itemid = 152;
?>


<div class="mod-custom mod-membership white-bg section-padding-tb" >
	<div class="grid-container">
    <div class="top center">
      <h2><?php echo $module->title; ?></h2>
	  	<h5><?php echo $module->content; ?></h5>
    </div>
		
		  
		<div class="member-levels uk-margin-medium-top section-padding-bottom">
      <div uk-grid>
        <?php 
          $i = 1; 
          foreach ($list as $item) 
          {
            //$link     = JRoute::_( RSMembershipRoute::Membership($item->id, $itemid) );
            $apply_link = $helper->Subscribe( $item->category_id, $item->id, $item->name, $itemid );
            //$apply_link = "";
            $price    = ($item->price);
            $imagesrc    ='components/com_rsmembership/assets/thumbs/'.$item->thumb;
           
            ?>
              <div class="uk-width-1-1 uk-width-1-3@s rsm_container">
                <div class="thumbnail card-member center">
                  <img src="<?php echo $imagesrc; ?>"/>
                  <div class="caption">
                    <h3 class="rsm_title"><?php echo $item->name; ?></h3>
                    
                    <?php echo $item->description; ?>
                    <h2 class="rsm_title">$<?php echo $price; ?></h2>
                    <p class="subtitle">Annual Membership</p>
                    <div class="clearfix"></div>
                    <div class="row-fluid">
                      <div class="btn-group">
                        

                          <a href="<?php echo $apply_link; ?>" class="button uk-margin-medium-top">Join Now</a>

                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                </div>
                <div class="card-member-bottom">
                  <a href="/membership/levels">
                    <span>See what’s included</span>
                    <i class="icon icon-eye"></i>
                  </a>
                </div>
              </div>

          <?php $i++; ?>
        <?php } ?>
      </div>

  	</div>
	
</div>

