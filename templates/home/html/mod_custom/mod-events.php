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
$list = $helper->getEvents();
$itemid = 111;
?>
<?php 
    $allCats['slug'] = array();
    $allCats['title'] = array();
    $allTypes['slug'] = array();
    $allTypes['title'] = array();
    foreach ($list as $item): 
      $cat =$helper->getCategory($item->id);
        array_push($allCats['slug'],str_replace(' ','-',strtolower($cat->id)));
        array_push($allCats['title'],$cat->title);
    
      $type =$helper->getType($item->id);
        array_push($allTypes['slug'],str_replace(' ','-',strtolower($type->id)));
        array_push($allTypes['title'],$type->title);
     
    endforeach;
    $allCats['slug'] = array_unique($allCats['slug']);
    $allCats['title'] = array_unique($allCats['title']);
    $allTypes['slug'] = array_unique($allTypes['slug']);
    $allTypes['title'] = array_unique($allTypes['title']);

    $allDays['slug'] = array ('Mon','Tue','Wed','Thu','Fri','Sat','SUN');
    $allDays['title'] = array ('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY');
?>

<div class="mod-custom mod-events events-list light-bg section-padding-tb" >
	<div class="grid-container">
    <div class="top">
      <h2><?php echo $module->title; ?></h2>
	  	<div uk-grid id="filter-btn" class="">
	    	<div class="uk-width-1-2 uk-width-auto@s">
	          <div class="select-outer">
	            <select class="filter-select-days uk-select">
	              <option value="">Weekdays</option>
	              <?php foreach ($allDays['slug'] as $key=>$allDay): ?>
	                <option value="<?php echo $allDays['slug'][$key];?>"><?php echo $allDays['title'][$key];?></option>
	              <?php endforeach;?>
	            </select>
	          </div>
	        </div>
	        
	        <div class="uk-width-1-2 uk-width-auto@s">
	          <div class="select-outer">
	            <select class="filter-select-type uk-select">
	              <option value="">Event Types</option>
	              <?php foreach ($allTypes['slug'] as $key=>$allType): ?>
	                <option value="<?php echo $allTypes['slug'][$key];?>"><?php echo $allTypes['title'][$key];?></option>
	              <?php endforeach;?>
	            </select>
	          </div>
	        </div>

	        <div class="uk-width-1-2 uk-width-auto@s">
	          <div class="select-outer">
	            <select class="filter-select-catid uk-select">
	              <option value="">Any Category</option>
	              <?php foreach ($allCats['slug'] as $key=>$allCat): ?>
	                <option value="<?php echo $allCats['slug'][$key];?>"><?php echo $allCats['title'][$key];?></option>
	              <?php endforeach;?>
	            </select>
	          </div>
	        </div>
	    </div>
    </div>
		<!--filter original-->

    <form class="filter filter-original">
      <div class="filter-group">      
        <span id="day-" class="day-filter" data-value=".day-">Weekdays</span>
        <?php foreach ($allDays['slug'] as $key=>$allDay): ?>
          <span id="day-<?php echo $allDays['slug'][$key];?>" class="day-filter" data-value=".day-<?php echo $allDays['slug'][$key];?>"><?php echo $allDays['title'][$key]?></span>
        <?php endforeach;?>

        <span id="type-" class="type-filter" data-value=".type">Event Type</span>
        <?php foreach ($allTypes['slug'] as $key=>$allType): ?>
          <span  id="type-<?php echo $allTypes['slug'][$key];?>" class="type-filter" data-value=".type-<?php echo $allTypes['slug'][$key];?>"><?php echo $allTypes['title'][$key]?></span>
        <?php endforeach;?>

        <span id="catid-" class="catid-filter" data-value=".catid">Any Category</span>
        <?php foreach ($allCats['slug'] as $key=>$allCat): ?>
          <span class="catid-filter" id="catid-<?php echo $allCats['slug'][$key];?>" data-value=".catid-<?php echo $allCats['slug'][$key];?>"><?php echo $allCats['title'][$key]?></span>
        <?php endforeach;?>

      </div>
    </form>

    <!--//filter original-->
		    
		  
		<div class="slick uk-margin-medium-top">
		 	<?php foreach ($list as $item): ?>
        
        <?php
          $linky = JURI::base().substr(JRoute::_('index.php?option=com_events&view=details&id='.$item->id.':'.JFilterOutput::stringURLSafe($item->title).'&Itemid='.$itemid),strlen(JURI::base(true))+1);

            $sdate=date_create($item->stime);
            $ddate = date_format($sdate,"l jS F Y");

            $edate=date_create($item->etime);
       
        ?>
      
        <div class="slide catid catid-<?php echo $item->catid;?> type type-<?php echo $item->type;?> day- day-<?php echo date_format($sdate,"D");?> card-event">
            <a href="<?php echo $linky;?>">
              <div class="item ">
                  <div class="image">
                    <img src="<?php echo $item->thumb;?>"/>     
                  </div>
                  <div class="content white-bg">
                    <div class="content-date">
                      <p class="month"><?php echo date_format($sdate,"M");?></p>
                      <p class="day"><?php echo date_format($sdate,"j");?></p>
                    </div>
                    <div class="content-content">
                      <h6><?php echo $item->title; ?></h6>
                      <p class="time"><?php echo date_format($sdate,"H:i")." - ".date_format($edate,"H:i");?></p>
                      <p><?php echo $item->intro; ?></p>
                      <p class="readmore">Read More</p>
                    </div>
                  </div>
  
              </div>
            </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="progress uk-margin-medium-top" role="progressbar" aria-valuemin="0" aria-valuemax="100">
        <span class="slider__label sr-only"></span>
    </div>

    <a href="" class="button uk-margin-medium-top">VIEW ALL EVENTS</a>
      

	</div>
	
</div>

