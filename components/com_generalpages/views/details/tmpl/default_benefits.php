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

$modName = 'mod_custom'; 
$modTitle = 'Membership Levels';
$_mod_levels = JModuleHelper::getModule($modName, $modTitle);
$mod_levels = JModuleHelper::renderModule($_mod_levels);

//related generalpages
/*if(isset($item->relatedgeneralpages) ) {
  $relatedGeneralpages = GeneralpagesHelper::getRelatedGeneralpages($item->relatedgeneralpages );
}*/
?>
<div class="generalpages-detail">

  <div class="layout4 section-padding-top white-bg">
    <div class="grid-container-medium">
      <?php $layout4s = json_decode($item->cards);
          $cardstitle = $layout4s->cardstitle; 
          $cardsintro = $layout4s->cardsintro; 
          $cardsthumb = $layout4s->cardsthumb; 
      ?>
      <?php if ($item->cardstitle):?>
        <h2 class="center"><?php echo $item->cardstitle;?></h2>
      <?php endif;?>
      <?php if ($item->cardsintro):?>
        <p class="center uk-margin-medium-bottom"><?php echo $item->cardsintro;?></p>
      <?php endif;?>
      <div uk-grid class="layout4-grid uk-child-width-auto">
      <?php foreach ($cardstitle as $key => $layout4) :?>
      
        <div class="uk-width-1-1 uk-width-1-4@s">
          <div class="content center">
              <img class="" src="<?php echo $cardsthumb[$key];?>"/>
              <h6><?php echo $cardstitle[$key];?></h6>
              <?php echo $cardsintro[$key];?>
          </div>
        </div>
        
      
      <?php endforeach;?>
      </div>
    </div>
  </div>

  <div class="light-bg">
    <?php echo $mod_levels;?>
  </div>

  <div class="section-padding-tb light-bg">
    <div class="grid-container">
      <h2 class="uk-text-center"><?php echo $item->subtitle1;?></h2>
      <div class="article-default uk-text-center layout-cta">   
        <?php echo $item->body1;?>
      </div>
      
    </div>
  </div>

 

  


</div>


