<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipTableSubscriber extends JTable
{
	public function __construct(& $db) 
	{
		parent::__construct('#__rsmembership_subscribers', 'user_id', $db);
	}
}