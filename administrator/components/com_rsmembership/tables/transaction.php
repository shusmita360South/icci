<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipTableTransaction extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) 
	{
		parent::__construct('#__rsmembership_transactions', 'id', $db);
	}

    public function check()
    {
        require_once JPATH_ADMINISTRATOR . '/components/com_rsmembership/helpers/helper.php';
        $this->ip = RSMembershipHelper::getIP();

        return true;
    }
}