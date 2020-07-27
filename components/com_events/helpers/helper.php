<?php
# no direct access
defined('_JEXEC') or die;

abstract class EventsHelper
{
	public static function ellipsis($text,$length=100,$options=array())
	{
		$default = array(
			'ending' => '...', 'exact' => true, 'html' => false
		);
		$options = array_merge($default, $options);
		extract($options);
		if ($html) {
			if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			$totalLength = mb_strlen(strip_tags($ending));
			$openTags = array();
			$truncate = '';
			preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
			foreach ($tags as $tag) {
				if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
					if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
						array_unshift($openTags, $tag[2]);
					} else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
						$pos = array_search($closeTag[1], $openTags);
						if ($pos !== false) {
							array_splice($openTags, $pos, 1);
						}
					}
				}
				$truncate .= $tag[1];
				$contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
				if ($contentLength + $totalLength > $length) {
					$left = $length - $totalLength;
					$entitiesLength = 0;
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
						foreach ($entities[0] as $entity) {
							if ($entity[1] + 1 - $entitiesLength <= $left) {
								$left--;
								$entitiesLength += mb_strlen($entity[0]);
							} else {
								break;
							}
						}
					}
					$truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
					break;
				} else {
					$truncate .= $tag[3];
					$totalLength += $contentLength;
				}
				if ($totalLength >= $length) {
					break;
				}
			}
		} else {
			if (mb_strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = mb_substr($text, 0, $length - mb_strlen($ending));
			}
		}
		if (!$exact) {
			$spacepos = mb_strrpos($truncate, ' ');
			if (isset($spacepos)) {
				if ($html) {
					$bits = mb_substr($truncate, $spacepos);
					preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
					if (!empty($droppedTags)) {
						foreach ($droppedTags as $closingTag) {
							if (!in_array($closingTag[1], $openTags)) {
								array_unshift($openTags, $closingTag[1]);
							}
						}
					}
				}
				$truncate = mb_substr($truncate, 0, $spacepos);
			}
		}
		$truncate .= $ending;
		if ($html) {
			foreach ($openTags as $tag) {
				$truncate .= '</'.$tag.'>';
			}
		}
		return $truncate;
	}
	
	public static function getItemid($title)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__menu');
		$query->where('title="'.$title.'"');
        $db->setQuery($query->__toString());
		$rows = $db->loadResult();

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

	public static function getPageIntro()
	{
		$id = 2;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__content');
		$query->where('state=1');
		$query->where('id='.$id);
		$db->setQuery($query->__toString(),0,4);
		$rows = $db->loadObject();
#		echo '<pre>'; print_r($rows); echo '</pre>';
		return $rows;
		
	}
	public static function getDownloadFiles($id){
				
		if ($id) : 
		
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
	
			$query->select('*');
			$query->from('#__downloads_items');
			$query->where('catid='.$id);
			$query->where('state=1');
			$query->order('ordering asc');
			$db->setQuery($query->__toString());
			$result = $db->loadObjectList();	
			
			return $result;			
		endif;
	}
	public static function getCurentCategory($catid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from('#__events_categories');
		$query->where('id = '.$catid);
		$query->where('state=1');
		
        $db->setQuery($query->__toString());
		$rows = $db->loadObject();

		return $rows;
		
	}
	

	public static function getEvents() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__events_items'))
		    ->where($db->quoteName('state') . ' = 1')
		    ->order('ordering ASC');
		$db->setQuery($query);
		$events = $db->loadObjectList();

		return $events;
	}	

	
	
	

}

