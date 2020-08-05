<?php
# no direct access
defined('_JEXEC') or die;

# seo stuff
$doc  = JFactory::getDocument();
$title  = $doc->getTitle();
$menu   = JFactory::getApplication()->getMenu();

#$parent = $menu->getItem($menu->getActive()->parent_id)->title;
#$current = $menu->getItem($menu->getActive()->id)->title;

if (isset($this->items[0]->metatitle))
  $doc->setTitle($this->items[0]->metatitle);
if ($this->items[0]->metakeys)
  $doc->setMetaData('keywords', $this->items[0]->metakeys);
if ($this->items[0]->metadesc) {
  $doc->setDescription($this->items[0]->metadesc);
} else {
  $doc->setDescription( ContactformHelper::ellipsis(strip_tags($this->items[0]->body),255) );
}

$app    = JFactory::getApplication();
$menu     = $app->getMenu();
$active   = $menu->getActive();
$params   = new JRegistry;
$params->loadString($active->params);
$pageTitle = $params->get('page_heading');
$contactimge = $app->getParams()->get('menu_image');

$menuparent = $menu->getItem($active->parent_id);
if ($menuparent) {
  $menuparentname = $menuparent->title;
}


#echo "<pre>"; print_r($pageheading); echo "</pre>";

if (isset($_SESSION['response_code'])) {
  $response_code = $_SESSION['response_code'];
  unset($_SESSION['response_code']);
} else {
  $response_code = 0;
}

$contact_form = $this->items[0];

$doc->addScript('https://www.google.com/recaptcha/api.js');
$doc->setTitle( $title );
//var_dump($app->getParams());
// Getting params from template
$tempparams = $app->getTemplate(true)->params;

//render breadcrumb module
$modNameBreadcrumbs = 'mod_breadcrumbs'; 
$modTitleBreadcrumbs = 'Breadcrumbs';
$_mod_breadcrumb = JModuleHelper::getModule($modNameBreadcrumbs, $modTitleBreadcrumbs);
$mod_breadcrumb = JModuleHelper::renderModule($_mod_breadcrumb);


?>
<div class="page-header section-padding-tb light-bg">
  <div class="grid-container">
    <?php echo $mod_breadcrumb;?>
    <h1><?php echo $pageTitle;?></h1>
  </div>
</div>
<div class="contact-item section-padding-tb">
  <div class="grid-container-small">

    <div uk-grid class="">

      <div class="uk-width-1-1 uk-width-2-3@s">
        <div class="content-left">
          <?php echo $contact_form->body; ?>
          <div class="form-outer"> 
            <?php if ($response_code == 1) : // thankyou ?>
              <div class="alert alert-success alert-dismissible" role="alert">

                  <?php if ($contact_form->thankyou) : ?>
                      <?php echo $contact_form->thankyou; ?>
                  <?php else : ?>
                      <p>Your message was sent successfully.</p>
                  <?php endif; ?>
              </div>
              <script>
              var trackerName;
              $( document ).ready(function() {
                  var formposition = $("#contact-form").position();

                  $('html, body').animate({
                      scrollTop: formposition.top - 100
                  }, 100);

                  $('#contact-form').hide();
                  $('#intro p').hide();

              });
              </script>
              <?php elseif ($response_code == 2) : // error ?>
                  <div class="alert alert-danger alert-dismissible" role="alert">

                      <p>There was an error processing your form, please try again:</p>
                  </div>
                  <script>
                  $( document ).ready(function() {
                      var formposition = $("#contact-form").position();

                      $('html, body').animate({
                          scrollTop: formposition.top - 100
                      }, 100);
                  });
                  </script>
              <?php elseif ($response_code == 3) : // spam ?>
                  <div class="alert alert-danger alert-dismissible" role="alert">

                      <p>Unusual activity detected, please try again. If this problem persists, consider contacting us directly.</p>
                  </div>
                  <script>
                  $( document ).ready(function() {
                      var formposition = $("#contact-form").position();
                      console.log (formposition);
                      $('html, body').animate({
                          scrollTop: formposition.top - 100
                      }, 100);
                      console.log ("end");
                  });
                  </script>
            <?php endif; ?>
            <form class="uk-form-stacked uk-margin-medium-top" name="contact-form" id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post">
                  <div uk-grid class="uk-grid-small">
                      <div class="uk-width-1-1 uk-width-1-1@s">
                          <div class="uk-margin">
                              <div class="uk-form-controls">
                                  <select class="uk-select chosen-select" name="c_reason" id="c_reason">
                                      <option value="General Enquiry">WHAT CAN WE HELP YOU WITH?</option>
                                      <option value="Apply for a career at Multiworks">Apply for a career at Multiworks</option>
                                      <option value="Enquire about our services">Enquire about our services</option>
                                      <option value="Pricing a tender">Pricing a tender</option>
                                      <option value="ECI Engagement">ECI Engagement</option>
                                  </select>
                              </div>
                          </div>
                      </div>
                      <div class="uk-width-1-1 uk-width-1-1@s">
                          <div class="uk-margin">
                              <div class="uk-form-controls">
                                  <span class="input--kaede input">
                                    <input type="text" name="c_fname" id="c_fname" class="required uk-input input__field input__field--kaede" value="<?php echo isset($_SESSION['c_fname']) ? $_SESSION['c_fname'] : ''; ?>" />
                                    <label class="input__label input__label--kaede" for="input-35">
                                        <span class="input__label-content input__label-content--kaede">Name*</span>
                                      </label>
                                  </span>
                              </div>
                          </div>
                      </div>
                      <div class="uk-width-1-1 uk-width-1-1@s">
                          <div class="uk-margin">
                              <div class="uk-form-controls">
                                  <span class="input--kaede input">
                                    <input type="email" name="c_email" id="c_email" class="required uk-input input__field input__field--kaede" value="<?php echo isset($_SESSION['c_email']) ? $_SESSION['c_email'] : ''; ?>" />
                                    <label class="input__label input__label--kaede" for="input-35">
                                        <span class="input__label-content input__label-content--kaede">Email*</span>
                                      </label>
                                  </span>
                              </div>
                          </div>
                      </div>
                      <div class="uk-width-1-1 uk-width-1-1@s">
                          <div class="uk-margin">
                              <div class="uk-form-controls">
                                  <span class="input--kaede input">
                                    <input type="text" name="c_phone" id="c_phone" class=" uk-input input__field input__field--kaede" value="<?php echo isset($_SESSION['c_phone']) ? $_SESSION['c_phone'] : ''; ?>" />
                                    <label class="input__label input__label--kaede" for="input-35">
                                        <span class="input__label-content input__label-content--kaede">Phone</span>
                                      </label>
                                  </span>
                              </div>
                          </div>
                      </div>
                      <div class="uk-width-1-1 uk-width-1-1@s">
                          <div class="uk-margin">
                              <div class="uk-form-controls">
                                  <span class="input--kaede input">
                                    <input type="text" name="c_company" id="c_company" class=" uk-input input__field input__field--kaede" value="<?php echo isset($_SESSION['c_company']) ? $_SESSION['c_company'] : ''; ?>" />
                                    <label class="input__label input__label--kaede" for="input-35">
                                        <span class="input__label-content input__label-content--kaede">Company</span>
                                      </label>
                                  </span>
                              </div>
                          </div>
                      </div>
                      <div class="uk-width-1-1 uk-width-1-1@s">
                          <div class="uk-margin">
                              <div class="uk-form-controls">
                                  <textarea name="c_message" id="c_message" rows="5" placeholder="MESSAGE" class="uk-textarea" ><?php if (isset($_SESSION['c_message'])) echo $_SESSION['c_message']; ?></textarea>
                              </div>
                          </div>
                      </div>
                      <div class="uk-width-1-1 uk-width-1-1@s">
                          <div class="uk-margin g-recaptcha-outer">
                              <div class="g-recaptcha" data-theme="light" data-sitekey="6LccwS8UAAAAAMV_c_7n4zVmDAgudcf2PVf88_tn"></div>
                          </div>
                      </div>
                      <div class="uk-width-1-1 uk-width-1-1@s">
                          <div class="uk-margin"> 
                                           
                              <div class="button-outer">
                                  <button class="button btn-orange" data-pjax-state="" type="submit" name="submit">Submit</button>
                              </div>
                          </div>
                      </div>
                  </div>
                  <input type="hidden" name="option" value="com_contactform" />
                  <input type="hidden" name="task" value="form.contact_submit" />
                  <?php echo JHtml::_('form.token'); ?>
              </form>
          </div>
        </div>
      </div>

      <div class="uk-width-1-1 uk-width-1-3@s">
        <div class="content-right">

          <div class="">
              <h4>Victoria</h4>
              <p>
                  <?php $toBeReplaced = array("<br />", "/");?>
                  <a href="https://www.google.com/maps/place/<?php echo str_replace($toBeReplaced," ",nl2br($tempparams->get('vaddress')));?>" target="_blank"><?php echo nl2br($tempparams->get('vaddress'));?></a><br/>
                 
                  <span class="">P</span> <a href="tel:+61<?php echo str_replace( " ", "", ( substr( $tempparams->get('vphone'), 0, 1 ) === '0' ? substr( $tempparams->get('vphone'), 1 ) : $tempparams->get('vphone') ) );?>"><?php echo $tempparams->get('vphone');?></a><br/>
                  <span class="">F</span> <a href="tel:+61<?php echo str_replace( " ", "", ( substr( $tempparams->get('vfax'), 0, 1 ) === '0' ? substr( $tempparams->get('vfax'), 1 ) : $tempparams->get('vfax') ) );?>"><?php echo $tempparams->get('vfax');?></a><br/>
                  <span class="">E</span> <a href="mailto:<?php echo $tempparams->get('vemail');?>"><?php echo $tempparams->get('vemail');?></a>
              </p>
          </div>

          <div class="uk-margin-medium-top">
              <h4>New South Wales</h4>
              <p>
                  <?php $toBeReplaced = array("<br />", "/");?>
                  <a href="https://www.google.com/maps/place/<?php echo str_replace($toBeReplaced," ",nl2br($tempparams->get('naddress')));?>" target="_blank"><?php echo nl2br($tempparams->get('naddress'));?></a><br/>
                 
                  <span class="">P</span> <a href="tel:+61<?php echo str_replace( " ", "", ( substr( $tempparams->get('nphone'), 0, 1 ) === '0' ? substr( $tempparams->get('nphone'), 1 ) : $tempparams->get('nphone') ) );?>"><?php echo $tempparams->get('nphone');?></a><br/>
                  <span class="">F</span> <a href="tel:+61<?php echo str_replace( " ", "", ( substr( $tempparams->get('nfax'), 0, 1 ) === '0' ? substr( $tempparams->get('nfax'), 1 ) : $tempparams->get('nfax') ) );?>"><?php echo $tempparams->get('nfax');?></a><br/>
                  <span class="">E</span> <a href="mailto:<?php echo $tempparams->get('nemail');?>"><?php echo $tempparams->get('nemail');?></a>
              </p>
          </div>

          <div class="contact-social">        
              <a href="<?php echo $tempparams->get('tweet');?>"><i class="icon-tweet"></i></a>
              <a href="<?php echo $tempparams->get('facebook');?>"><i class="icon-facebook"></i></a>
              <a href="<?php echo $tempparams->get('linkedin');?>"><i class="icon-linkedin"></i></a>
              <a href="<?php echo $tempparams->get('instagram');?>"><i class="icon-instagram"></i></a>  
          </div>

        </div>
      </div>

    </div>

  </div>
</div>




          
          

      
            
                       
                  

                             
                            
                       
      



                               
                         




