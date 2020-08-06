<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipPatchesHelper
{
	public static function getPatchFile($type)
	{
		if ($type == 'menu') 
		{
			return JPATH_SITE.'/modules/mod_menu/helper.php';
		}
		elseif ($type == 'module')
		{
			return JPATH_SITE.'/libraries/cms/module/helper.php';
		}
	}
	
	public static function checkMenuShared(&$rows) {} // No longer used

	public static function getModulesWhere()
	{
		return '';
	}
}