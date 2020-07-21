<?php

defined('_JEXEC') or die;

class TempHelper
{
	public static function getTemplatePara() {

		$app        = JFactory::getApplication();
		$template   = $app->getTemplate(true);
		$templateParams     = $template->params;

		return $templateParams;

	}

	// public static function getContactForm() {
	// 	$db  = JFactory::getDbo();
	// 	$query = $db->getQuery(true);
	// 	$query->select('*')
	// 	    ->from($db->quoteName('#__contactform_items'))
	// 	    ->where($db->quoteName('id') . ' = 1');
	// 	$db->setQuery($query);
	// 	$contact_form = $db->loadObject();

	// 	return $contact_form;
	// }	

	public static function getServices() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__projects_services'))
		    ->where($db->quoteName('state') . ' = 1');
		$query->order('ordering DESC');
		$db->setQuery($query);
		$services = $db->loadObjectList();

		return $services;
	}	

	public static function getSectors() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__projects_sectors'))
		    ->where($db->quoteName('state') . ' = 1');
		$query->order('ordering DESC');
		$db->setQuery($query);
		$services = $db->loadObjectList();

		return $services;
	}	

	public static function getBannersSafety() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__banners'))
		    ->where($db->quoteName('state') . ' = 1')
		    ->where($db->quoteName('catid') . ' = 8');
		$query->order('ordering DESC');
		$db->setQuery($query);
		$services = $db->loadObjectList();

		return $services;
	}	
	public static function getProjects() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__projects_items'))
		    ->where($db->quoteName('state') . ' = 1');
		$query->order('ordering DESC');
		$db->setQuery($query);
		$services = $db->loadObjectList();

		return $services;
	}	
	public static function getCategories($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('pc.title');
		$query->from($db->quoteName('#__projects_items_to_categories','pic'));
		$query->join('LEFT', $db->quoteName('#__projects_categories', 'pc') . ' ON (' . $db->quoteName('pc.id') . ' = ' . $db->quoteName('pic.cat_id') . ')');
		$query->where('item_id = '.$id);

        $db->setQuery($query->__toString());
		$rows = $db->loadRowList();

		return $rows;
		
	}
	public static function getSectorsForProjects($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('pc.icon, pc.title');
		$query->from($db->quoteName('#__projects_items_to_sector','pic'));
		$query->join('LEFT', $db->quoteName('#__projects_sectors', 'pc') . ' ON (' . $db->quoteName('pc.id') . ' = ' . $db->quoteName('pic.sector_id') . ')');
		$query->where('item_id = '.$id);

        $db->setQuery($query->__toString());
		$rows = $db->loadRowList();

		return $rows;
		
	}
	public static function getNews()
	{
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__news_items'))
		    ->where($db->quoteName('state') . ' = 1')
		    ->setLimit(3)
		    ->order('date DESC');
		$db->setQuery($query);
		$news = $db->loadObjectList();

		return $news;
		
	}

	public static function getPageGallery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$id = JRequest::getVar('Itemid');
		$query->select('*');
		$query->from('#__gallery_items');
		$query->where('state = 1');
		$query->where('pageid = '.$id);
        $db->setQuery($query->__toString());
		$rows = $db->loadObjectList();

		return $rows;
		
	}
	

	
}



?>