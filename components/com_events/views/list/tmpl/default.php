<?php
# no direct access
defined('_JEXEC') or die;
#echo '<pre>'; print_r($this->items); echo '</pre>';

$app        = JFactory::getApplication();
$menu       = $app->getMenu();
$active     = $menu->getActive();
$menuparent = $menu->getItem($active->parent_id);


$params   = new JRegistry;
$params->loadString($active->params);
$itemid = JRequest::getVar('Itemid');
$pageTitle = $params->get('page_heading');

$list = $this->items;

//helper


// Getting params from template
$tempparams = $app->getTemplate(true)->params;

//render breadcrumb module
$modNameBreadcrumbs = 'mod_breadcrumbs'; 
$modTitleBreadcrumbs = 'Breadcrumbs';
$_mod_breadcrumb = JModuleHelper::getModule($modNameBreadcrumbs, $modTitleBreadcrumbs);
$mod_breadcrumb = JModuleHelper::renderModule($_mod_breadcrumb);



?>

  <div class="js-filter events-list section-padding-tb light-bg">
    <div class="grid-container">
      <h1 class="center"><?php echo $pageTitle;?></h1>


      <div uk-grid id="filter-btn" class="">
        <?php 
            $allCats['slug'] = array();
            $allCats['title'] = array();
            $allTypes['slug'] = array();
            $allTypes['title'] = array();
            foreach ($list as $item): 
              $cat = EventsHelper::getCategory($item->id);
                array_push($allCats['slug'],str_replace(' ','-',strtolower($cat->id)));
                array_push($allCats['title'],$cat->title);
            
              $type = EventsHelper::getType($item->id);
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
        <div class="uk-width-1-1 uk-width-auto@s">
          <div class="select-outer">
            <select class="filter-select-days uk-select">
              <option value="0">Weekdays</option>

              <?php foreach ($allDays['slug'] as $key=>$allDay): ?>
                <option value="<?php echo $allDays['slug'][$key];?>"><?php echo $allDays['title'][$key];?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
        
        <div class="uk-width-1-1 uk-width-auto@s">
          <div class="select-outer">
            <select class="filter-select-type uk-select">
              <option value="0">Event Types</option>
              <?php foreach ($allTypes['slug'] as $key=>$allType): ?>
                <option value="<?php echo $allTypes['slug'][$key];?>"><?php echo $allTypes['title'][$key];?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>

        <div class="uk-width-1-1 uk-width-auto@s">
          <div class="select-outer">
            <select class="filter-select-catid uk-select">
              <option value="0">Any Category</option>
              <?php foreach ($allCats['slug'] as $key=>$allCat): ?>
                <option value="<?php echo $allCats['slug'][$key];?>"><?php echo $allCats['title'][$key];?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
        
      </div>

      <div class="" uk-filter="target: .js-filter">
        <div class="filter-original uk-grid-small uk-grid-divider uk-child-width-auto" uk-grid>
            <div>
                <ul class="uk-subnav uk-subnav-pill" uk-margin>
                    <li id="day-0" uk-filter-control="group: data-day"><a href="#">Weekdays</a></li>
                    <?php foreach ($allDays['slug'] as $key=>$allDay): ?>
                       <li id="day-<?php echo $allDays['slug'][$key];?>" uk-filter-control="filter: [data-day='<?php echo $allDays['slug'][$key];?>']; group: data-day"><a href="#"><?php echo $allDays['title'][$key]?></a></li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div>
                <ul class="uk-subnav uk-subnav-pill" uk-margin>
                    <li id="type-0" uk-filter-control="group: data-type"><a href="#">Event Type</a></li>
                    <?php foreach ($allCats['slug'] as $key=>$allCat): ?>
                       <li id="type-<?php echo $allTypes['slug'][$key];?>" uk-filter-control="filter: [data-type='<?php echo $allTypes['slug'][$key];?>']; group: data-type"><a href="#"><?php echo $allTypes['title'][$key]?></a></li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div>
                <ul class="uk-subnav uk-subnav-pill" uk-margin>
                    <li id="catid-0" uk-filter-control="group: data-catid"><a href="#">Any Category</a></li>
                    <?php foreach ($allCats['slug'] as $key=>$allCat): ?>
                       <li id="catid-<?php echo $allCats['slug'][$key];?>" uk-filter-control="filter: [data-catid='<?php echo $allCats['slug'][$key];?>']; group: data-catid"><a href="#"><?php echo $allCats['title'][$key]?></a></li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>

        <div uk-grid class="js-filter uk-margin-medium-top">
          <?php foreach ($list as $item): ?>
        
            <?php
              $linky = JURI::base().substr(JRoute::_('index.php?option=com_events&view=details&id='.$item->id.':'.JFilterOutput::stringURLSafe($item->title).'&Itemid='.$itemid),strlen(JURI::base(true))+1);

              $sdate=date_create($item->stime);
              $ddate = date_format($sdate,"l jS F Y");

              $edate=date_create($item->etime);
              $date_now = date("Y-m-d"); 

              if ($date_now < date_format($sdate,"Y-m-d")) :
           
            ?>
          
              <div data-day="<?php echo date_format($sdate,"D");;?>" data-catid="<?php echo $item->catid;?>" data-type="<?php echo $item->type;?>" class="card-event uk-width-1-1 uk-width-1-4@s">
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
            <?php endif; ?>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </div>


