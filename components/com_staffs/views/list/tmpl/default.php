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

  <div class="js-filter staffs-list section-padding-tb light-bg">
    <div class="grid-container">
      <h1 class="center"><?php echo $pageTitle;?></h1>


      
      <div class="" >
        
        <div uk-grid class="uk-margin-large-top">
          <?php foreach ($list as $item): ?>
        
            <?php
              //$linky = JURI::base().substr(JRoute::_('index.php?option=com_staffs&view=details&id='.$item->id.':'.JFilterOutput::stringURLSafe($item->title).'&Itemid='.$itemid),strlen(JURI::base(true))+1);

              
            ?>
          
            <div class="card-event card-staff uk-width-1-1 uk-width-1-4@s">

                  <div class="item ">
                      <div class="image">
                        <img src="<?php echo $item->image;?>"/>     
                      </div>
                      <div class="content white-bg">
                        
                        <div class="content-content">
                          <h6><?php echo $item->title; ?></h6>
                          
                          <p class="subtitle"><?php echo $item->subtitle;?>
                          <?php if($item->company): ?>
                            <br> <?php echo $item->company; ?>
                          <?php endif; ?>
                          </p>
                          <?php if($item->email): ?>
                            <p><a class="_icon-outer" href="mailto:<?php echo $item->email; ?>" target="_blank"><span class="icon-outer"><span uk-icon="icon: mail"></span></span></a>
                          <?php endif; ?>
                          <?php if($item->linkedin): ?>
                            <a class="_icon-outer" href="<?php echo $item->linkedin; ?>" target="_blank"><span class="icon-outer"><span uk-icon="icon: linkedin"></span></span></a>
                          <?php endif; ?></p>
                        </div>
                      </div>
                  </div>

            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </div>


