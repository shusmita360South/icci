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

$modNameMenu = 'mod_menu'; 
$modTitleMenu = 'Sidebar Menu';
$_mod_menu = JModuleHelper::getModule($modNameMenu, $modTitleMenu);
$mod_menu = JModuleHelper::renderModule($_mod_menu);


?>
<div class="page-header section-padding-tb light-bg">
  <div class="grid-container">
    <?php echo $mod_breadcrumb;?>
    <h1><?php echo $pageTitle;?></h1>
  </div>
</div>
<div class="grid-container-small events-list section-padding-tb">
  <div uk-grid>
    <div class="uk-width-1-1 uk-width-1-3@s events-intro">
      <h1 class="mod-title">The latest from Multiworks</h1>
      <p>Here you will find the latest information and events from Multiworks.</p>
    </div>
    <div class="uk-width-1-1 uk-width-2-3@s">
      <div class="events-items">
        <?php foreach($list as $item) : ?>   
          <?php
            $linky = JURI::base().substr(JRoute::_('index.php?option=com_events&view=details&id='.$item->id.':'.JFilterOutput::stringURLSafe($item->title).'&Itemid='.$itemid),strlen(JURI::base(true))+1);
            $date = date_create($item->date);
            $date = date_format($date,"d.m.Y");
           
          ?>
          <div class="item">
            <a class="" href="<?php echo $linky;?>">
              <p class="date"><?php echo $date; ?></p>
              <p class="title"><strong><?php echo $item->title; ?></strong></p>
              <p><?php echo $item->intro; ?></p>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  
</div>

