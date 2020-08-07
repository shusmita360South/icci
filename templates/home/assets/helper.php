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

	
	
	public static function getEvents() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__events_items'))
		    ->where($db->quoteName('state') . ' = 1');
		$query->order('ordering DESC');
		$db->setQuery($query);
		$services = $db->loadObjectList();

		return $services;
	}	
	public static function getServices() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__services_items'))
		    ->where($db->quoteName('state') . ' = 1');
		$query->order('ordering DESC');
		$db->setQuery($query);
		$services = $db->loadObjectList();

		return $services;
	}	
	public static function getMemberlevels() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__rsmembership_memberships'))
		    ->where($db->quoteName('published') . ' = 1');
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

	public static function getCategory($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from('#__events_categories');
		$query->where('id = '.$id);

        $db->setQuery($query->__toString());
		$rows = $db->loadObject();

		return $rows;
		
	}
	public static function getType($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from('#__events_types');
		$query->where('id = '.$id);

        $db->setQuery($query->__toString());
		$rows = $db->loadObject();

		return $rows;
		
	}

	public static function Subscribe( $catid, $membership_id, $membership_name, $Itemid ) 
	{
		$catidurl = '';
		

		$membershipurl = '';
		if ( $membership_id ) 
		{
			$membershipurl = '&cid='.$membership_id.':'.JFilterOutput::stringURLSafe($membership_name);
		}

		$Itemidurl = '';
		if ( $Itemid ) 
		{
			$Itemidurl = '&Itemid='.$Itemid;
		}

		return 'index.php?option=com_rsmembership&task=subscribe'.$catidurl.$membershipurl.$Itemidurl;
	}

	public static function getMemberExtras() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__rsmembership_extras'))
		    ->where($db->quoteName('published') . ' = 1');
		$query->order('ordering DESC');
		$db->setQuery($query);
		$extras = $db->loadObjectList();

		return $extras;
	}
	public static function getMemberItemExtra($itemId, $extraId) {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__rsmembership_membership_extras'))
		    ->where($db->quoteName('extra_id') . '='.$extraId)
		    ->where($db->quoteName('membership_id'). '=' . $itemId);
		$db->setQuery($query);
		$extras = $db->loadObject();

		if ($extras)
			return '<i uk-icon="icon: check; ratio: 1.5"></i>';
	}

	
}



?>