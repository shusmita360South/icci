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

//related generalpages
/*if(isset($item->relatedgeneralpages) ) {
  $relatedGeneralpages = GeneralpagesHelper::getRelatedGeneralpages($item->relatedgeneralpages );
}*/
?>
<div class="page-header-2 light-bg section-padding-top section-padding-bottom-half">
  <div class="grid-container-medium">
    
    <h1><?php echo $item->title;?></h1>
    <h5><?php echo $item->intro;?></h5>
  </div>
</div>
<div class="page-header-2-image uk-text-center">
  <img src="<?php echo $item->image1;?>"/>
</div>


<?php 
if($item->template == 1) {
  echo $this->loadTemplate('general');
}
 
elseif($item->template == 2) {
  echo $this->loadTemplate('aboutus');
}
?>





