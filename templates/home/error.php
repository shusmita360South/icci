<?php
# no direct access
defined( '_JEXEC' ) or die;
date_default_timezone_set('Australia/Melbourne');

# variables
$app    = JFactory::getApplication();
$doc    = JFactory::getDocument();
$params   = $app->getParams();
$pageclass  = $params->get('pageclass_sfx');
$tpath    = $this->baseurl.'/templates/home';

$this->setGenerator(null);

$menu     = $app->getMenu();
$active   = $menu->getActive();
$params   = new JRegistry;
$params->loadString($active->params);
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

# seo stuff
$title = $doc->getTitle();
$doc->setTitle($title);

# remove scrips
unset($this->_script['text/javascript']);

# add stylesheets

$doc->addStyleSheet( $tpath.'/css/font-awesome.min.css' );
$doc->addStyleSheet( $tpath.'/css/flexboxgrid.css' );
$doc->addStyleSheet( $tpath.'/css/uikit.min.css' ); //uikit v2
$doc->addStyleSheet( $tpath.'/css/styles.css' );

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


<link rel="stylesheet" href="<?php echo $tpath; ?>/css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tpath; ?>/css/flexboxgrid.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $tpath; ?>/css/styles.css" type="text/css" />


</head>
<body class="site <?php echo $option
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($params->get('fluidContainer') ? ' fluid' : '');
    echo ($this->direction === 'rtl' ? ' rtl' : '');
?>">


<main id="main" class="is-visible">  
    <div class="container">
        <jdoc:include type="component" />
    </div>
    <div id="container">
        <div class="article-content page404 default">
            <div class="grid-container uk-text-center">
                <a href="/"><img src="<?php echo $tpath; ?>/images/logo.svg" width="300"/></a>
                <h2>That page can’t be found.</h2>
                <p>It looks like nothing was found at this location. Maybe try:</p>

                <?php 
                $showSubmenu = false;
                $this->submenumodules = JModuleHelper::getModules('mainmenu');
                foreach ($this->submenumodules as $submenumodule)
                {
                    echo $output = JModuleHelper::renderModule($submenumodule);
                    if (strlen($output))
                    {
                        $showSubmenu = true;
                        break;
                    }
                }

                //$module = JModuleHelper::getModule('custom','Our team'); print_r($module);echo JModuleHelper::renderModule($module); ?>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/modernizr/2.8.3/modernizr.min.js"></script>
<script src="<?php echo $tpath; ?>/js/validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.4/SmoothScroll.min.js"></script>
</body>
</html>