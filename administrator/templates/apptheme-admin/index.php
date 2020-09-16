<?php
/**
 * @package     AppTheme
 * @subpackage  Templates.apptheme
 *
 * @copyright   Copyright (C) 2005 - 2012 Pixel Praise LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
$user  = JFactory::getUser();

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add Stylesheets
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

// Load optional rtl Bootstrap css and Bootstrap bugfixes
JHtmlBootstrap::loadCss($includeMaincss = false, $this->direction);

// Add current user information
$user = JFactory::getUser();

// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span6";
}
elseif ($this->countModules('position-7') && !$this->countModules('position-8'))
{
	$span = "span9";
}
elseif (!$this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span9";
}
else
{
	$span = "span12";
}

$showSubmenu = false;
$this->submenumodules = JModuleHelper::getModules('submenu');
foreach ($this->submenumodules as $submenumodule)
{
	$output = JModuleHelper::renderModule($submenumodule);
	if (strlen($output))
	{
		$showSubmenu = true;
		break;
	}
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="'. JURI::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';
}
else if ($this->params->get('theme') == "flat")
{
	$logo = '<img src="'. JURI::root() .'administrator/templates/' .$this->template. '/images/logo-flat.png" alt="'. $sitename .'" />';
}
else if ($this->params->get('theme') == "carbon" || $this->params->get('theme') == "sepia" || $this->params->get('theme') == "ironman" || $this->params->get('theme') == "masterchief")
{
	$logo = '<img src="'. JURI::root() .'administrator/templates/' .$this->template. '/images/logo-inverse.png" alt="'. $sitename .'" />';
}
else
{
	$logo = '<img src="'. JURI::root() .'administrator/templates/' .$this->template. '/images/logo.png" alt="'. $sitename .'" />';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="navy" />
	<jdoc:include type="head" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/apple-touch-icon-57-precomposed.png">
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
	<?php if ($this->params->get('theme') == "dribbble"): ?>
		<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
	<?php endif; ?>
	<?php if ($this->params->get('theme') == "masterchief"): ?>
		<link href='http://fonts.googleapis.com/css?family=Share+Tech' rel='stylesheet' type='text/css'>
	<?php endif; ?>
	<?php if ($this->params->get('theme') == "flat"): ?>
		<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<style type="text/css">
			.flat .navigation, .flat .breadcrumb, .flat .page-title, .flat .footer
			{
				background: <?php echo $this->params->get('templateColor'); ?>;
			}
		</style>
	<?php endif; ?>
</head>

<body class="<?php echo $this->params->get('theme', 'mac'); ?> site <?php echo $option . " view-" . $view . " layout-" . $layout . " task-" . $task . " itemid-" . $itemid . " ";?>">
	<!-- Body -->
	<div class="body">
		<div class="container-fixed">
			<?php if ($this->params->get('navigation', 1)): ?>
			<div class="navigation">
				<ul class="nav nav-pills pull-right">
					<li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $user->name; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class=""><a href="index.php?option=com_admin&task=profile.edit&id=<?php echo $user->id;?>"><?php echo JText::_('TPL_APPTHEME_EDIT_ACCOUNT');?></a></li>
							<li class="divider"></li>
							<li class=""><a href="<?php echo JRoute::_('index.php?option=com_login&task=logout&'. JSession::getFormToken() .'=1');?>"><?php echo JText::_('TPL_APPTHEME_LOGOUT');?></a></li>
						</ul>
					</li>
				</ul>
				<div class="search pull-right">
					<jdoc:include type="modules" name="search" style="none" />
				</div>
				<a class="brand pull-left" href="<?php echo $this->baseurl; ?>">
						<?php echo $logo;?>
					</a>
				<jdoc:include type="modules" name="menu" style="none" />
			</div>
			<?php endif; ?>
			<div class="row-fixed">
				<!-- Begin Sidebar -->
				<div id="sidebar" class="sidebar">
					<a class="btn btn-small btn-inverse pull-right sidebar-toggle visible-phone" href="#"><span aria-hidden="true" class="icon-remove"></span></a>
					<div class="sidebar- tablet-nav">
						<h3><?php echo JHtml::_('string.truncate', $sitename, 20, false, false);?></h3>
						<jdoc:include type="modules" name="menu" style="none" />
					</div>
					<div class="sidebar-">
						<div class="moduletable">
							<h3>Submenu</h3>
							<jdoc:include type="modules" name="submenu" style="none" />
							<div id="sidebar-replace"></div>
							<div id="sidebar-replace2"></div>
						</div>
					</div>
				</div>
				<!-- End Sidebar -->
				<div id="content" class="content">
					<div class="page-title center">
						<a class="btn <?php if ($this->params->get('theme') == "carbon" || $this->params->get('theme') == "sepia" || $this->params->get('theme') == "ironman" || $this->params->get('theme') == "masterchief" || $this->params->get('theme') == "flat") { echo "btn-inverse"; } ?> pull-left sidebar-toggle visible-phone" href="#"><span aria-hidden="true" class="icon-list-view"></span></a>
						<div class="btn-group pull-right title-nav">
							<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span aria-hidden="true" class="icon-pencil"></span></a>
							<ul class="dropdown-menu">
								<?php if($user->authorise('core.manage', 'com_content')) { ?>
									<li><a href="index.php?option=com_content&task=article.add"><?php echo JText::_( 'TPL_APPTHEME_NEW_ARTICLE' )?></a></li>
								<?php } ?>
								<?php if($user->authorise('core.manage', 'com_content')) { ?>
									<li><a href="index.php?option=com_categories&scope=content&task=category.add"><?php echo JText::_( 'TPL_APPTHEME_NEW_CATEGORY' )?></a></li>
								<?php } ?>
								<?php if($user->authorise('core.manage', 'com_menus')) { ?>
									<li><a href="index.php?option=com_menus&task=menu.add"><?php echo JText::_( 'TPL_APPTHEME_NEW_MENU' )?></a></li>
								<?php } ?>
								<?php if($user->authorise('core.manage', 'com_modules')) { ?>
									<li><a href="index.php?option=com_modules&view=select"><?php echo JText::_( 'TPL_APPTHEME_NEW_MODULE' )?></a></li>
								<?php } ?>
								<?php if($user->authorise('core.manage', 'com_users')) { ?>
									<li><a href="index.php?option=com_users&task=user.add"><?php echo JText::_( 'TPL_APPTHEME_NEW_USER' )?></a></li>
								<?php } ?>
								<?php if($user->authorise('core.manage', 'com_users')) { ?>
									<li><a href="index.php?option=com_users&task=group.add"><?php echo JText::_( 'TPL_APPTHEME_NEW_GROUP' )?></a></li>
								<?php } ?>
								<?php if($user->authorise('core.manage', 'com_installer')) { ?>
									<li><a href="index.php?option=com_installer"><?php echo JText::_( 'TPL_APPTHEME_NEW_EXTENSION' )?></a></li>
								 <?php } ?>
							</ul>
						</div>
						<?php if ($this->countModules('title-nav')) : ?>
							<div class="btn-group pull-left title-nav visible-desktop">
								<a class="btn <?php if ($this->params->get('theme') == "carbon" || $this->params->get('theme') == "ironman" || $this->params->get('theme') == "masterchief") { echo "btn-inverse"; } ?> dropdown-toggle" data-toggle="dropdown" href="#"><span aria-hidden="true" class="icon-list"></span></a>
								<jdoc:include type="modules" name="title-nav" style="none" />
							</div>
						<?php endif; ?>
						<?php echo JHtml::_('string.truncate', $app->JComponentTitle, 0, false, false);?>
					</div>
					<div class="content-inner">
						<a name="top"></a>
						<!-- Begin Content -->
						<jdoc:include type="modules" name="toolbar" style="none" />
						<jdoc:include type="modules" name="top" style="xhtml" />
						<div class="row-fluid">
								<div class="span12">
								<jdoc:include type="message" />
								<jdoc:include type="component" />
								</div>
						</div>
						<jdoc:include type="modules" name="bottom" style="xhtml" />
						<!-- End Content -->
					</div>
				</div>
				<?php if ($this->countModules('aside')) : ?>
				<div id="aside" class="aside">
					<!-- Begin Right Sidebar -->
					<jdoc:include type="modules" name="aside" style="well" />
					<!-- End Right Sidebar -->
				</div>
				<?php endif; ?>
			</div>
			<div class="footer">
				<div class="navigation-status btn-toolbar pull-left">
					<jdoc:include type="modules" name="status" style="none" />
				</div>
				<jdoc:include type="modules" name="tabbar" style="none" />
				<jdoc:include type="modules" name="debug" style="none" />
				<ul class="nav nav-pills pull-right">
					<li><a href="#top" id="back-top"><?php echo JText::_('TPL_APPTHEME_BACKTOTOP'); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	!function ($) {
		$(function(){
			// Sidebar Toggle
			$(".sidebar-toggle").click(function(){
				$('.sidebar').toggle();
			    return false;
			});
			// Add pills class to top nav
			$("#menu.nav").addClass("nav-pills");
			
			// Replace inner sidebar with outer
			$('#sidebar-replace').replaceWith($('#submenu'));
			$('#sidebar-replace2').replaceWith($('.com_config .span2 .sidebar-nav'));
			
			$('#j-sidebar-container').append( $('.filter-select') );
			
			$("#sidebar .nav.nav-list").removeClass("nav-list");
			$("#sidebar .nav .active").addClass("current");
			// Sidebar Toggle H3
			$('.sidebar h3').click(function(e) {
			 e.preventDefault();
			 $(this).siblings().toggle("fast");
			});
			/*
			// Remove dropdown from sidebar nav
			$("#menu.nav li").removeClass("dropdown");
			$("#menu.nav li ul").removeClass("dropdown-menu");
			$("#menu.nav li.dropdown-submenu").removeClass("dropdown-submenu");
			*/
			// Remove pills class from sidebar position-0
			$(".tablet-nav ul").removeClass("nav-pills");
			// Remove pills class from sidebar title nav
			$(".title-nav ul").removeClass("nav-pills").removeClass("nav").removeClass("menu").addClass("dropdown-menu");

			// Turn radios into btn-group
		    $('.radio.btn-group label').addClass('btn');
		    $(".btn-group label:not(.active)").click(function() {
		        var label = $(this);
		        var input = $('#' + label.attr('for'));

		        if (!input.prop('checked')) {
		            label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
		            if(input.val()== '') {
		                    label.addClass('active btn-primary');
		             } else if(input.val()==0) {
		                    label.addClass('active btn-danger');
		             } else {
		            label.addClass('active btn-success');
		             }
		            input.prop('checked', true);
		        }
		    });
		    $(".btn-group input[checked=checked]").each(function() {
				if($(this).val()== '') {
		           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
		        } else if($(this).val()==0) {
		           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
		        } else {
		            $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
		        }
		    });
		    ///////////////////////////////////////////////////////////////////// 
		    ///////////////////media upload membership form//////////////////////
		    /////////////////////////////////////////////////////////////////////

		    $('.logo-upload-btn').on('click', function() {

		        var logofile = $('input.memberlogo')[0].files[0];
		        var demoImageSrc;
		        var demoImage = document.querySelector('img#imgContainer');

		        var file = document.querySelector('input[type=file]').files[0];
		        var reader = new FileReader();
		        reader.onload = function (event) {
		            demoImage.src = reader.result;
		            demoImageSrc = event.target.result;
		        }
		        reader.readAsDataURL(file);
		        $('#imgContainer').addClass('hide');
		        setTimeout(function(){
		            
		            $.ajax({
		                url: '/index.php?option=com_contactform&task=form.rsmembership_logouplod',
		                type: "POST",
		                data: {"logofilename": logofile['name'], "logofilesize": logofile['size'], "logofiletype": logofile['type'], "logofiledata": demoImage.src},
		          
		                success: function(data)

		                    {
		                        console.log(data['error']);
		                      	 
		                        if(data['error'])
		                        {
		                         // invalid file format.
		                            $(".logo-upload-error").html(data['error']).fadeIn();
		                        }
		                        else
		                        {
		                            //$('#imgContainer').addClass('show');
		                            $('#imgLogoContainer').attr("src","/images/logo/"+data['file']);
		                        }
		                    },
		                error: function(e) 
		                    {
		                        $("#err").html(e).fadeIn();
		                        alert('upload error');
		                    }          
		            });
		        }, 30);

		    }); //END: media upload membership form


		});
	}(window.jQuery)
	</script>
</body>
</html>
