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

//render service module
$modNameGeneralpages = 'mod_custom'; 
$modTitleGeneralpages = 'Our Generalpages';
$_mod_service = JModuleHelper::getModule($modNameGeneralpages, $modTitleGeneralpages);
$mod_service = JModuleHelper::renderModule($_mod_service);


echo $mod_service; 
?>

 