<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerAjax extends JControllerLegacy
{
	public function __construct() {
		parent::__construct();
	}
	
	public function addmembershipshared() 
	{
		JFactory::getApplication()->input->set('view', 'membership', 'string');
		JFactory::getApplication()->input->set('layout', 'edit_shared_list', 'string');

		parent::display();
	}

	public function addsubscriberfiles() 
	{
		$jinput = JFactory::getApplication()->input;
		$jinput->set('view', 'membership', 'string');
		$jinput->set('layout', 'edit_files', 'string');

		parent::display();
	}

	public function addextravaluefolders() 
	{
		JFactory::getApplication()->input->set('view', 'extravalue', 'string');
		JFactory::getApplication()->input->set('layout', 'edit_shared_list', 'string');

		parent::display();
	}

	public function date() {
		$model = $this->getModel('Membership_Subscriber');
		$input = RSInput::create();
		
		$membership_id 		= $input->get('membership_id', 0, 'int');
		$membership_start	= $input->get('membership_start', '', 'raw');
		
		echo $model->getEndDate($membership_id, $membership_start);
		
		JFactory::getApplication()->close();
	}
}