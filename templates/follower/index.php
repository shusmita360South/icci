<?php
# no direct access
defined( '_JEXEC' ) or die;
date_default_timezone_set('Australia/Melbourne');

# variables
$app        = JFactory::getApplication();
$doc        = JFactory::getDocument();
$params = $app->getTemplate(true)->params;
$pageclass  = $params->get('pageclass_sfx');
$tpath      = $this->baseurl.'/templates/home';

$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');
$this->setGenerator(null);

$menu       = $app->getMenu();
$active     = $menu->getActive();
// Getting params from template



# seo stuff
$title = $doc->getTitle();
$doc->setTitle($title);

# remove scripts
unset($this->_script['text/javascript']);

# add stylesheets
$doc->addStyleSheet( $tpath.'/assets/css/vendor.css' );
$doc->addStyleSheet( $tpath.'/assets/css/styles.css' );

$option = JRequest::getVar('option');
$view   = JRequest::getVar('view');


// Getting params from template
$params = $app->getTemplate(true)->params;
$termsLinky = "/terms-and-conditions";
$privacyLinky ="/privacy-policy";


?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en">
<!--<![endif]-->

<head>
<meta charset="utf-8">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="theme-color" content="#000000">

<jdoc:include type="head" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet"> 
<script src="<?php echo $tpath; ?>/assets/js/vendor.min.js"></script>
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo $tpath; ?>/assets/images/favicon/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/assets/images/favicon/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/assets/images/favicon/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $tpath; ?>/assets/images/favicon/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?php echo $tpath; ?>/assets/images/favicon/apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo $tpath; ?>/assets/images/favicon/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo $tpath; ?>/assets/images/favicon/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo $tpath; ?>/assets/images/favicon/apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="<?php echo $tpath; ?>/assets/images/favicon/favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="<?php echo $tpath; ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="<?php echo $tpath; ?>/assets/images/favicon/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="<?php echo $tpath; ?>/assets/images/favicon/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="<?php echo $tpath; ?>/assets/images/favicon/favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="mstile-310x310.png" />

</head>
<body class="home site <?php echo $option
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($params->get('fluidContainer') ? ' fluid' : '');
    echo ($this->direction === 'rtl' ? ' rtl' : '');
?>">
<div class="loader-outer"><div class="loader"></div> </div>
<main id="main" class="is-visible">
    <div class="container0">
        
        <header uk-sticky="offset: 0">
            <div class="container">
                
                <nav class="uk-navbar" id="navbar" >
                    <div class="uk-navbar-left">
                        <a class="logo" href="/"><img alt="ICCI Melbourne" src="<?php echo $tpath; ?>/assets/images/logo.svg"/></a>
                    </div>
                    
                    <div class="uk-navbar-right">
                        <jdoc:include type="modules" name="mainmenu" /> 
                        <a href="/membership/levels" class="button btn-blue menu-btn uk-visible@m">Join Now</a>
                        <a href="<?php echo $this->params->get('facebook');?>"><span class="icon-outer"><span uk-icon="icon: facebook"></span></span></a>
                        <a href="<?php echo $this->params->get('linkedin');?>"><span class="icon-outer"><span uk-icon="icon: linkedin"></span></span></a>
                        <a href="<?php echo $this->params->get('instagram');?>"><span class="icon-outer"><span uk-icon="icon: instagram"></span></span></a>
                        <div class="uk-navbar-toggle-outer">
                            <a class="uk-navbar-toggle uk-hidden@m"  data-uk-toggle uk-toggle="target: #offcanvas-push"><i class="fas fa-bars"></i></a>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <!--<section class="home-banner">
           <video id="videobcg" preload="auto" loop="loop" autoplay muted playsinline volume="0" poster="/images/hero.jpg" width="100%" height="100%">
                <source src="images/video/hero-0.mp4" type="video/mp4">
                Sorry, your browser does not support HTML5 video.
            </video>
            <a uk-scroll href="#about" class="scrollto" style="display: block;">
                <span class="icon"></span>
            </a>
        </section>-->
        <jdoc:include type="modules" name="home-banner" />
        <jdoc:include type="modules" name="position-1" />
        <jdoc:include type="component" />
        <jdoc:include type="modules" name="position-2" /> 
        

         <footer>

            <div class="footer-top section-padding-top">

                <div class="grid-container">
                    <div class="logo-outer">
                        
                        <a class="logo" href="/"><img alt="ICCI Melbourne" src="<?php echo $tpath; ?>/assets/images/logo-white.svg"/></a>
                    </div>
                </div>
                
                <div class="grid-container uk-margin-medium-top">
                    <div class="uk-grid" data-uk-grid >
                        <div class="uk-width-1-1 uk-width-auto@s uk-width-2-6@m newsletter-outer">
                            
                            <h4>Subscribe to our newsletters</h4>
                            <form action="https://italcham.us14.list-manage.com/subscribe/post?u=e77767e9869f5167a9a3c2fff&amp;id=bdb1b7f344" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                    
                                <div class="uk-margin">
                                    <div class="uk-form-controls">
                                       
                                        <input type="email" value="" name="EMAIL" class="uk-input required email" id="mce-EMAIL" placeholder="youremail@example.com"><input class="submit" type="submit" value='' name="subscribe" id="mc-embedded-subscribe" >
                                    </div>
                                </div>
                                <div class="">
                                    <div class="uk-form-controls">
                                        <div id="mce-responses" class="clear">
                                            <div class="response" id="mce-error-response" style="display:none; color: #fff;"></div>
                                            <div class="response" id="mce-success-response" style="display:none; color: #fff;"></div>
                                        </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_bcf2386ff78d9047bf3aabf9d_75d689336e" tabindex="-1" value=""></div>
                                       
                                    </div>
                                </div>
                                    
                            </form>
                            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='MMERGE5';ftypes[5]='text';fnames[6]='MMERGE6';ftypes[6]='number';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                            <!--End mc_embed_signup-->
                        </div>
                        
                        <div class="uk-width-1-1 uk-margin-auto-left uk-width-1-4@s uk-width-1-6@m hidden-tablet">
                            <h4 class="orange">About</h4>
                            <jdoc:include type="modules" name="footermenu1" />
                        </div>
                        <div class="uk-width-1-1 uk-width-1-4@s uk-width-1-6@m hidden-tablet">
                            <h4 class="orange">Membership</h4>
                            <jdoc:include type="modules" name="footermenu2" />
                        </div>
                        <div class="uk-width-1-1 uk-width-1-4@s uk-width-1-6@m hidden-tablet">    
                            <h4 class="orange">Services</h4>    
                            <jdoc:include type="modules" name="footermenu3" />       
                        </div>
                        <div class="uk-width-1-1 uk-width-1-4@s uk-width-1-6@m hidden-tablet">    
                            <h4 class="orange">Events</h4>    
                            <jdoc:include type="modules" name="footermenu4" />       
                        </div>
                        
                    </div>
                   
                </div>

            </div>
            <div class="footer-middle uk-margin-medium-top">
                <div class="grid-container">
                    <div class="footer-copyright-top">
                        <span class="copy"><a href="/privacy-policy">Privacy Policy</a></span>
                        <span class="web"> 
                            <a href="<?php echo $this->params->get('facebook');?>"><span class="icon-outer"><span uk-icon="icon: facebook"></span></span></a>
                            <a href="<?php echo $this->params->get('linkedin');?>"><span class="icon-outer"><span uk-icon="icon: linkedin"></span></span></a>
                            <a href="<?php echo $this->params->get('instagram');?>"><span class="icon-outer"><span uk-icon="icon: instagram"></span></span></a>
                        </span>
                        <!-- <jdoc:include type="modules" name="footermenu" />  -->
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="grid-container">
                    <div class="footer-copyright">
                        <span class="copy">&copy; <?php echo date("Y"); ?> ICCI All Rights reserved</span>
                        <span class="web"><a href="https://www.360south.com.au/" target="_blank" title="Web Developers | Graphic Designers | Photography | 360South">Website By 360South.</a></span>
                        <!-- <jdoc:include type="modules" name="footermenu" />  -->
                    </div>
                </div>
            </div>

        </footer>
        <div class="offcanvas-push-outer">
            <div id="offcanvas-push" uk-offcanvas="mode: pull;  flip: true; container: true ">
                <div class="uk-offcanvas-bar">
                    <button class="uk-offcanvas-close" type="button" uk-close></button>
                   
                    
                    <jdoc:include type="modules" name="mainmenu" />
                   
                </div>
            </div>
        </div>
    </div>
</main>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='MMERGE5';ftypes[5]='text';fnames[6]='MMERGE6';ftypes[6]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<script src="<?php echo $tpath; ?>/assets/js/app.js"></script>

</body>
</html>