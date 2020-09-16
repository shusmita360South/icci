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
  <div class="layout1 section-padding-tb">
    <div class="grid-container-medium">
      <div uk-grid>
        <div class="uk-width-1-1 uk-width-1-3@s">
          <div class="content-left">
            <h2><?php echo $item->subtitle1;?></h2>
          </div>
        </div>
        <div class="uk-width-1-1 uk-width-2-3@s">
          <div class="content-right">
            <div class="article-default">
              <?php echo $item->body1;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if($item->cards):?>
  <div class="layout4 section-padding-tb light-bg">
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
        <p class="intro center uk-margin-medium-bottom"><?php echo $item->cardsintro;?></p>
      <?php endif;?>
      <div uk-grid class="layout4-grid uk-child-width-auto uk-flex-center">
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
  <?php endif;?>

  <?php if($item->cards2):?>
  <div class="layout5 section-padding-tb white-bg">
    <div class="grid-container-medium">
      <?php $layout5s = json_decode($item->cards2);
          $cardstitle2 = $layout5s->cards2title; 
          $cardsintro2 = $layout5s->cards2intro; 
          $cardsthumb2 = $layout5s->cards2thumb; 
      ?>
      <?php if ($item->cardstitle2):?>
        <h2 class="center"><?php echo $item->cardstitle2;?></h2>
      <?php endif;?>
      <?php if ($item->cardsintro2):?>
        <p class="intro center uk-margin-medium-bottom"><?php echo $item->cardsintro2;?></p>
      <?php endif;?>
      <div uk-grid class="layout5-grid uk-child-width-auto">
      <?php foreach ($cardstitle2 as $key => $layout5) :?>
      
        <div class="uk-width-1-1 uk-width-1-5@s">
          <div class="content center">
              <img class="" src="<?php echo $cardsthumb2[$key];?>"/>
              <h6><?php echo $cardstitle2[$key];?></h6>
              <?php echo $cardsintro2[$key];?>
          </div>
        </div>
        
      
      <?php endforeach;?>
      </div>
    </div>
  </div>
  <?php endif;?>


</div>

