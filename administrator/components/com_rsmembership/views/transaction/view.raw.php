<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSMembershipViewTransaction extends JViewLegacy
{
	protected $item;

	public function display($tpl = null) {
		// get subscriber
		$this->item = $this->get('Item');

		parent::display($tpl);
	}
}