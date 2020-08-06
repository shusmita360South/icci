<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipTableMembership extends JTable
{
	public function __construct(& $db) 
	{
		parent::__construct('#__rsmembership_memberships', 'id', $db);
	}

	public function check() 
	{
		if (!$this->id) {
			$this->ordering = $this->getNextOrder();
		}

		return true;
	}

	public function bind($src, $ignore=array())
	{
		$bound = parent::bind($src, $ignore);

		if ($bound)
		{
			if (isset($src['gid_subscribe']) && is_array($src['gid_subscribe']))
				$this->gid_subscribe = implode(',', $src['gid_subscribe']);
			if (isset($src['gid_expire']) && is_array($src['gid_expire']))
				$this->gid_expire = implode(',', $src['gid_expire']);
		}
		
		return $bound;
	}
}