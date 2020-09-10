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

if (isset($_SESSION['response_code'])) {
  $response_code = $_SESSION['response_code'];
  unset($_SESSION['response_code']);
} else {
  $response_code = 0;
}

$db     = JFactory::getDBO();
$query    = $db->getQuery(true);
$query->select('*')
          ->from('#__contactform_items')
          ->where('id = 3');
$db->setQuery($query);
$contact_form = $db->loadObject();

$doc->addScript('https://www.google.com/recaptcha/api.js');
?>

  
<div class="form-outer section-padding-bottom grey-bg">
  <div class="grid-container-small  white-bg"> 
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
      <form class="uk-form-stacked" name="contact-form" id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post">
            <div uk-grid class="uk-grid-small">

                <div class="uk-width-1-1 uk-width-1-2@s">
                    <div class="uk-margin">
                        <div class="uk-form-controls">

                            <div class="uk-form-label">First Name*</div>
                            <input required type="text" name="c_fname" id="c_fname" class="uk-input" value="<?php echo isset($_SESSION['c_fname']) ? $_SESSION['c_fname'] : ''; ?>" />
                             
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-1-2@s">
                    <div class="uk-margin">
                        <div class="uk-form-controls">

                              <div class="uk-form-label">Last Name*</div>
                              <input required  type="text" name="c_lname" id="c_lname" class=" uk-input" value="<?php echo isset($_SESSION['c_lname']) ? $_SESSION['c_lname'] : ''; ?>" />
                            
                        </div>
                    </div>
                </div>
                
                <div class="uk-width-1-1 uk-width-1-2@s">
                    <div class="uk-margin">
                        <div class="uk-form-controls">
                              <div class="uk-form-label">Email*</div>
                              <input required  type="email" name="c_email" id="c_email" class=" uk-input" value="<?php echo isset($_SESSION['c_email']) ? $_SESSION['c_email'] : ''; ?>" />
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-1-2@s">
                    <div class="uk-margin">
                        <div class="uk-form-controls">
                              <div class="uk-form-label">Phone</div>
                              <input type="text" name="c_phone" id="c_phone" class=" uk-input" value="<?php echo isset($_SESSION['c_phone']) ? $_SESSION['c_phone'] : ''; ?>" />
                        </div>
                    </div>
                </div>
                

                <div class="uk-width-1-1 uk-width-1-1@s">
                    <div class="uk-margin">
                        <div class="uk-form-controls">
                            <div class="uk-form-label">Message</div>
                            <textarea name="c_message" id="c_message" rows="5" placeholder="" class="uk-textarea" ><?php if (isset($_SESSION['c_message'])) echo $_SESSION['c_message']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-1-1@s">
                    <div class="uk-margin g-recaptcha-outer">
                      <div class="uk-form-controls">
                        <div class="g-recaptcha" data-theme="light" data-sitekey="6LccwS8UAAAAAMV_c_7n4zVmDAgudcf2PVf88_tn"></div>
                      </div>
                    </div>
                </div>
                <div class="uk-width-1-1 uk-width-1-1@s">
                    <div class="uk-margin"> 
                      <div class="uk-form-controls">              
                        <div class="button-outer">
                            <button class="button" data-pjax-state="" type="submit" name="submit">Submit</button>
                        </div>
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

