<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewThankYou extends JViewLegacy
{
	public function display( $tpl = null )
	{
		// get parameters
		$this->params  = clone(JFactory::getApplication()->getParams('com_rsmembership'));
		$this->message = $this->get('message');
		
		parent::display();
	}
}