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
  	
  	<?php if($item->videolink):?>
	  	<div class="video-wrap">
	  		<a class="video-btn image-outer" href="<?php echo $item->videolink;?>"> 
	  		<img src="<?php echo $item->image1;?>"/>
	    	<div class="play">
	    		<svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
    				<path class="circle" d="M95.0963 48.0885C95.0963 74.1022 74.0137 95.1848 48 95.1848C21.9864 95.1848 0.903687 74.1022 0.903687 48.0885C0.903687 22.0749 21.9864 0.992188 48 0.992188C74.0137 0.992188 95.0963 22.0749 95.0963 48.0885Z" fill="#ED1C1C"></path>
   					<path class="arrow" d="M42.6176 56.162V40.0146L58.7649 48.0883L42.6176 56.162Z" fill="white"></path>
    			</svg>
    		</div>
    		</a>
	  	</div>
	<?php else: ?>
		<img src="<?php echo $item->image1;?>"/>
	<?php endif;?>

</div>


<?php 
if($item->template == 1) {
  echo $this->loadTemplate('general');
}
 
elseif($item->template == 2) {
  echo $this->loadTemplate('aboutus');
}
elseif($item->template == 3) {
  echo $this->loadTemplate('trueitaliantaste');
}
?>





