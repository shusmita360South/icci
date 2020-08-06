<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class RSMembershipViewSubscriber extends JViewLegacy
{
	protected $item;

	public function display($tpl = null) {
		// get subscriber
		$this->item = $this->get('Item');

		// check if tmpl is set
		$jinput = JFactory::getApplication()->input;
		$this->tmpl = $jinput->get('tmpl', '', 'cmd');

		parent::display($tpl);
	}
}