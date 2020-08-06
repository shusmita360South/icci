<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerShare_url extends JControllerLegacy
{

	public function addMembershipURL()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$jform  = JFactory::getApplication()->input->get('jform', array(), 'array');
		$cid 	= $jform['id'];
		
		// Get the model
		$model = $this->getModel('share_url');
		
		$model->addMembershipURL($cid);
		jexit();
	}

	public function addExtraValueURL()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		// Get the selected items
		$jform  = JFactory::getApplication()->input->get('jform', array(), 'array');
		$cid 	= $jform['id'];

		// Get the model
		$model = $this->getModel('share_url');

		$model->addExtraValueURL($cid);
		jexit();
	}
}