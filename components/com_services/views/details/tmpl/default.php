<?php
# no direct access
defined('_JEXEC') or die;
$doc  = JFactory::getDocument();
$doc->addScript('https://addevent.com/libs/atc/1.6.1/atc.min.js');
#echo '<pre>'; print_r($this->items); echo '</pre>';
$item = $this->items;
$item = $item[0];
$itemid = JRequest::getVar('Itemid');


//render breadcrumb module
$modName = 'mod_breadcrumbs'; 
$modTitle = 'Breadcrumbs';
$_mod_breadcrumb = JModuleHelper::getModule($modName, $modTitle);
$mod_breadcrumb = JModuleHelper::renderModule($_mod_breadcrumb);

//related services
if(isset($item->relatedservices) ) {
  $relatedServices = ServicesHelper::getRelatedServices($item->relatedservices );
}
?>
<div class="page-header-2 light-bg section-padding-top section-padding-bottom-half">
  <div class="grid-container-medium">
    
    <h1><?php echo $item->title;?></h1>
    <h5><?php echo $item->lintro;?></h5>
  </div>
</div>
<div class="page-header-2-image uk-text-center">
  <img src="<?php echo $item->image;?>"/>
</div>
<div class="services-detail section-padding-tb">
  <div class="grid-container-medium">
    <div uk-grid>
      <div class="uk-width-1-1 uk-width-1-3@s">
        <div class="content-left">
          <h2><?php echo $item->subtitle;?></h2>
        </div>
      </div>
      <div class="uk-width-1-1 uk-width-2-3@s">
        <div class="content-right">
          <div class="article-default">
            <p><?php echo $item->intro;?></p>
            <?php echo $item->body;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




