<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerSyslogs extends JControllerAdmin
{
	public function __construct($config = array()) {
		parent::__construct($config);
		
		// delete
		$this->registerTask('trash', 'delete');
	}
	
	public function getModel($name = 'Syslog', $prefix = 'RSMembershipModel', $config = array('ignore_request' => true)) {
		return parent::getModel($name, $prefix, $config);
	}
}