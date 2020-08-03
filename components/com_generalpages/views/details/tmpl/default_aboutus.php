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
<div class="generalpages-detail">

  <div class="layout2 section-padding-tb">
    <div class="grid-container">
      <h2 class="uk-text-center"><?php echo $item->subtitle2;?></h2>
      <div class="article-default uk-text-center">   
        <?php echo $item->body2;?>
      </div>
      <img class="uk-margin-medium-top" src="<?php echo $item->image2;?>"/>
    </div>
  </div>

  <div class="layout3 section-padding-tb light-bg">
    <div class="grid-container-medium">
      <?php $layout3s = json_decode($item->twocolsbody);
          $twocolsbodytitle = $layout3s->twocolsbodytitle; 
          $twocolsbodyintro = $layout3s->twocolsbodyintro; 
          $twocolsbodythumb = $layout3s->twocolsbodythumb; 
      ?>
      <?php foreach ($twocolsbodytitle as $key => $layout3) :?>
      <div uk-grid class="layout3-grid">
        <div class="uk-width-1-1 uk-width-1-2@s">
          <div class="content-left">
            <div class="content-vcenter">
              <h2><?php echo $twocolsbodytitle[$key];?></h2>
              <?php echo $twocolsbodyintro[$key];?>
            </div>
          </div>
        </div>
        <div class="uk-width-1-1 uk-width-1-2@s">
          <div class="content-right">
            <img class="" src="<?php echo $twocolsbodythumb[$key];?>"/>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>

  <div class="layout4 section-padding-tb white-bg">
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
      
        <div class="uk-width-1-1 uk-width-1-5@s">
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


</div>


