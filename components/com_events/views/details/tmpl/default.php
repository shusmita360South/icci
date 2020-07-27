<?php
# no direct access
defined('_JEXEC') or die;
$doc  = JFactory::getDocument();
$doc->addScript('https://www.google.com/recaptcha/api.js');
#echo '<pre>'; print_r($this->items); echo '</pre>';
$item = $this->items;
$item = $item[0];
$itemid = JRequest::getVar('Itemid');


//render breadcrumb module
$modName = 'mod_breadcrumbs'; 
$modTitle = 'Breadcrumbs';
$_mod_breadcrumb = JModuleHelper::getModule($modName, $modTitle);
$mod_breadcrumb = JModuleHelper::renderModule($_mod_breadcrumb);

//contact form response code
if (isset($_SESSION['response_code'])) {
  $response_code = $_SESSION['response_code'];
  unset($_SESSION['response_code']);
} else {
  $response_code = 0;
}
?>
<div class="page-header section-padding-tb light-bg">
  <div class="grid-container">
    <?php echo $mod_breadcrumb;?>
    <h1><?php echo $item->title;?></h1>
  </div>
</div>
<div class="events-detail section-padding-tb">
  <div class="grid-container-small">

    <div class="image-outer">
      <div class="image">
        <?php if($item->videolink): ?>
          <a class="video-btn" href="<?php echo $item->videolink;?>">
            <img src="<?php echo $item->image1;?>"/> 
            <div class="video-play"></div>
          </a>
        <?php else: ?>
          <img src="<?php echo $item->image1;?>"/> 
        <?php endif;?>
      </div>
    </div>

    <div uk-grid class="section-padding-top-half">
      
      <div class="uk-width-1-1 uk-width-2-5@s">
        <div class="content-left">
          <h2><?php echo $item->subtitle;?></h2>
        </div>
      </div>
      <div class="uk-width-1-1 uk-width-3-5@s">
        <div class="content-right">
          <h3><?php echo $item->intro;?></h3>
          <?php echo $item->body;?>
        </div>
      </div>
    </div>

   
  </div>
</div>


