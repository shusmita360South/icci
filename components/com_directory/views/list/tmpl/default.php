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

$keyword = JRequest::getVar('keyword');

?>

  <div class="directory-list section-padding-tb light-bg">
    <div class="grid-container">
      <h1 class="center"><?php echo $pageTitle;?></h1>

      <form class="uk-margin-medium-top">
        <input name="keyword" placeholder="Keyword" class="uk-input" id="keyword-input" type="text" value="<?php echo $keyword?>"/>

        <select name="category" class="uk-select chosen-select">
          <option value="">All Category</option>

          <option value="Agriculture" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Agriculture")) {echo "selected";} ?>>Agriculture</option>   
          <option value="Architects/Design" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Architects/Design")) {echo "selected";} ?>>Architects/Design  </option> 
          <option value="Building" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Building")) {echo "selected";} ?>>Building </option> 
          <option value="Chemical Industry" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Chemical Industry")) {echo "selected";} ?>>Chemical Industry  </option>
          <option value="Education" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Education")) {echo "selected";} ?>>Education</option>  
          <option value="Electronics" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Electronics")) {echo "selected";} ?>>Electronics </option>  
          <option value="Energy" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Energy")) {echo "selected";} ?>>Energy </option> 
          <option value="Financial & Insurance" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Financial and Insurance")) {echo "selected";} ?>>Financial and Insurance</option>  
          <option value="Fishery" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Fishery")) {echo "selected";} ?>>Fishery  </option> 
          <option value="Food Industry" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Food Industry")) {echo "selected";} ?>>Food Industry  </option>
          <option value="Furniture and Furnishings" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Furniture and Furnishings")) {echo "selected";} ?>>Furniture and Furnishings  </option> 
          <option value="Health" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Health")) {echo "selected";} ?>>Health  </option>
          <option value="Hotels and Restaurants " <?php if (isset($_GET["category"]) && ($_GET["category"] == "Hotels and Restaurants")) {echo "selected";} ?>>Hotels and Restaurants  </option>
          <option value="Industries N.E.C. " <?php if (isset($_GET["category"]) && ($_GET["category"] == "Industries N.E.C.")) {echo "selected";} ?>>Industries N.E.C. </option>  
          <option value="Information Technology" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Information Technology")) {echo "selected";} ?>>Information Technology  </option> 
          <option value="Mechanical Engineering" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Mechanical Engineering")) {echo "selected";} ?>>Mechanical Engineering </option>  
          <option value="Media and Marketing" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Media and Marketing")) {echo "selected";} ?>>Media and Marketing  </option> 
          <option value="Metals" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Metals")) {echo "selected";} ?>>Metals</option> 
          <option value="Mineral Extraction" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Mineral Extraction")) {echo "selected";} ?>>Mineral Extraction  </option> 
          <option value="Other" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Other")) {echo "selected";} ?>>Other  </option>
          <option value="Publishing and Printing" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Publishing and Printing")) {echo "selected";} ?>>Publishing and Printing  </option>
          <option value="Real Estate" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Real Estate")) {echo "selected";} ?>>Real Estate </option>  
          <option value="Recreation" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Recreation")) {echo "selected";} ?>>Recreation </option>  
          <option value="Recycling" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Recycling")) {echo "selected";} ?>>Recycling </option> 
          <option value="Research and Development" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Research and Development")) {echo "selected";} ?>>Research and Development</option>   
          <option value="Services N.E.C" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Services N.E.C")) {echo "selected";} ?>>Services N.E.C  </option>
          <option value="Telecommunications" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Telecommunications")) {echo "selected";} ?>>Telecommunications </option> 
          <option value="Textile and Fashion Industry" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Textile and Fashion Industry")) {echo "selected";} ?>>Textile and Fashion Industry</option>   
          <option value="Timber" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Timber")) {echo "selected";} ?>>Timber  </option>
          <option value="Trade" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Trade")) {echo "selected";} ?>>Trade </option> 
          <option value="Transport" <?php if (isset($_GET["category"]) && ($_GET["category"] == "Transport")) {echo "selected";} ?>>Transport   </option>     
        </select>
        <button class="button btn-blue" type="submit" >Search</button>
      </form>  

      <div class="btn-group gz-alphanumeric-btn uk-margin-medium-top" role="group" aria-label="Select by alphanumeric">
        <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "0-9"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "0-9")) {echo "active";}?> gz-alpha-btns all">0-9</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "a")); ?>" class=" <?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "a")) {echo "active";}?> gz-alpha-btns ">A</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "b")); ?>" class=" <?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "b")) {echo "active";}?> gz-alpha-btns">B</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "c"));?>" class=" <?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "c")) {echo "active";}?> gz-alpha-btns">C</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "d"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "d")) {echo "active";}?> gz-alpha-btns">D</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "e"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "e")) {echo "active";}?> gz-alpha-btns">E</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "f"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "f")) {echo "active";}?> gz-alpha-btns">F</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "g"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "g")) {echo "active";}?> gz-alpha-btns">G</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "h"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "h")) {echo "active";}?> gz-alpha-btns">H</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "i"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "i")) {echo "active";}?> gz-alpha-btns">I</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "j"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "j")) {echo "active";}?> gz-alpha-btns">J</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "k"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "k")) {echo "active";}?> gz-alpha-btns">K</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "l"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "l")) {echo "active";}?> gz-alpha-btns">L</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "m"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "m")) {echo "active";}?> gz-alpha-btns">M</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "n"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "n")) {echo "active";}?> gz-alpha-btns">N</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "o"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "o")) {echo "active";}?> gz-alpha-btns">O</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "p"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "p")) {echo "active";}?> gz-alpha-btns">P</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "q"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "q")) {echo "active";}?> gz-alpha-btns">Q</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "r"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "r")) {echo "active";}?> gz-alpha-btns">R</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "s"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "s")) {echo "active";}?> gz-alpha-btns">S</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "t"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "t")) {echo "active";}?> gz-alpha-btns">T</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "u"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "u")) {echo "active";}?> gz-alpha-btns">U</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "v"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "v")) {echo "active";}?> gz-alpha-btns">V</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "w"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "w")) {echo "active";}?> gz-alpha-btns">W</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "x"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "x")) {echo "active";}?> gz-alpha-btns">X</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "y"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "y")) {echo "active";}?> gz-alpha-btns">Y</a>
               <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => "z"));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "z")) {echo "active";}?> gz-alpha-btns">Z</a>
                <a role="button" href="<?php echo $this->getCurrentSearch(array('searchalpha' => ""));?>" class="<?php if (isset($_GET["searchalpha"]) && ($_GET["searchalpha"] == "")) {echo "active";}?> gz-alpha-btns all">All</a>
        </div>              

      <div class="" >
       
        <div uk-grid class="uk-margin-medium-top">
          <?php foreach ($list as $item):// print_r($item)?>
        
           
          
            <div class="card-event card-directory uk-width-1-1 uk-width-1-4@s">

                  <div class="item ">
                      <div class="image">
                        <img src="/images/logo/<?php echo $item->f19;?>"/>     
                      </div>
                      <div class="content no-padding-bottom white-bg">
                       
                        <div class="content-content">
                          <h3><?php echo $item->f8; ?></h3>
                          <?php if($item->f23 == "Opt-In 2 Full"): ?>
                            <?php if($item->f1): ?>
                            <p><span class="icon-top" uk-icon="icon: location"></span> <span><?php echo $item->f1; ?>, <?php echo $item->f2; ?>, <?php echo $item->f3; ?> <?php echo $item->f4; ?>, <?php echo $item->f5; ?></span></p>
                            <?php endif; ?>

                            <?php if($item->f10): ?>
                            <p><span class="icon-top" uk-icon="icon: receiver"></span> <?php echo $item->f10; ?></p>
                            <?php endif; ?>

                            <?php if($item->f11): ?>
                            <p><span class="icon-top" uk-icon="icon: phone"></span> <?php echo $item->f11; ?></p>
                            <?php endif; ?>

                            <?php if($item->email): ?>
                            <p><span class="icon-top" uk-icon="icon: mail"></span> <?php echo $item->email; ?></p>
                            <?php endif; ?>

                            <?php if($item->f12): ?>
                            <p><span class="icon-top" uk-icon="icon: link"></span> <?php echo $item->f12; ?></p>
                            <?php endif; ?>

                    
                            <?php if($item->f14): ?>
                              <a class="_icon-outer" href="<?php echo $item->f14; ?>" target="_blank"><span class="icon-outer"><span uk-icon="icon: linkedin"></span></span></a>
                            <?php endif; ?>
                            <?php if($item->f15): ?>
                              <a class="_icon-outer" href="<?php echo $item->f15; ?>" target="_blank"><span class="icon-outer"><span uk-icon="icon: facebook"></span></span></a>
                            <?php endif; ?>
                            <?php if($item->f16): ?>
                              <a class="_icon-outer" href="<?php echo $item->f16; ?>" target="_blank"><span class="icon-outer"><span uk-icon="icon: twitter"></span></span></a>
                            <?php endif; ?>
                          <?php endif; ?>
                        </div>
                      </div>
      
                  </div>

            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </div>


