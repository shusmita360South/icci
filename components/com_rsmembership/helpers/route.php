<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSMembershipRoute
{

	public static function Categories( $layout="default" ) 
	{
		$layouturl = '&layout='.$layout;

		return 'index.php?option=com_rsmemberships&view=categories'.$layouturl;
	}

	public static function Subscribe( $catid,  $category_name, $membership_id, $membership_name, $Itemid ) 
	{
		$catidurl = '';
		if ( $catid ) 
		{
			$catidurl = '&catid='.(int) $catid.':'.JFilterOutput::stringURLSafe($category_name);
		}

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

	public static function Memberships( $catid=0, $Itemid=null, $layout="default" ) 
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$catid 	= (int) $catid;

		$layouturl = '&layout='.$layout;

		$catidurl = '';
		if ( $catid ) 
		{
			$query->select($db->qn('name'))->from($db->qn('#__rsmembership_categories'))->where($db->qn('id').' = '.$db->q($catid));
			$db->setQuery($query);
			$category = $db->loadObject();

			$catidurl = '&catid='.$catid.':'.JFilterOutput::stringURLSafe($category->name);
		}

		$ItemidUrl = '';
		if ( $Itemid ) 
			$ItemidUrl = '&Itemid='.(int) $Itemid;

		return 'index.php?option=com_rsmembership&view=rsmembership'.$layouturl.$catidurl.$ItemidUrl;
	}

	public static function Membership( $id, $Itemid=null ) 
	{
		$db 		= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$id 		= (int) $id;

		$query->select($db->qn('name'))->from($db->qn('#__rsmembership_memberships'))->where($db->qn('id').' = '.$db->q($id));
		$db->setQuery($query);
		$item_name = $db->loadResult();

		$ItemidUrl = '';
		if ( $Itemid ) 
			$ItemidUrl = '&Itemid='.(int) $Itemid;

		return 'index.php?option=com_rsmembership&view=membership&cid='.$id.':'.JFilterOutput::stringURLSafe($item_name).$ItemidUrl;
	}
	
	
	public static function Term( $id, $name, $tmpl=false ) 
	{
		$tmplurl = '';
		if ( $tmpl ) 
			$tmplurl = '&tmpl=component';

		return 'index.php?option=com_rsmembership&view=terms&cid='.$id.':'.JFilterOutput::stringURLSafe($name).$tmplurl;
	}
	
	public static function MyMemberships() 
	{
		return 'index.php?option=com_rsmembership&view=mymemberships';
	}

	public static function MyTransactions()
	{
		return 'index.php?option=com_rsmembership&view=mytransactions';
	}
	
	public static function MyMembership($membership_id) 
	{
		return 'index.php?option=com_rsmembership&view=mymembership&cid='.$membership_id;
	}

	public static function ThankYou() 
	{
		return 'index.php?option=com_rsmembership&task=thankyou';
	}
}