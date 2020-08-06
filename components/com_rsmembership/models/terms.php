<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelTerms extends JModelList
{
	public $message;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function getTerms()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', 0, 'int');
		$row = JTable::getInstance('Term','RSMembershipTable');

		$row->load($cid);

		if ( !$row->published ) 
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSMEMBERSHIP_NO_TERM'), 'warning');
			$app->redirect(JRoute::_(RSMembershipRoute::Memberships(), false));
		}
		return $row;
	}
}