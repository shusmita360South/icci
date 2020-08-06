<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2020 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipVersion
{
	public $version = '1.22.21';
	public $key		= 'MB86SH10F3';
	// Unused
	public $revision = null;
	
	public function __toString() {
		return $this->version;
	}
	
	// Legacy, keep revision
	public function __construct() {
		list($j, $revision, $bugfix) = explode('.', $this->version);
		$this->revision = $revision;
	}
}