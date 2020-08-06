<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerMemberships extends JControllerAdmin
{
	protected $text_prefix = 'COM_RSMEMBERSHIP';

	public function __construct($config = array()) 
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Membership', $prefix = 'RSMembershipModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function saveOrderAjax()
	{
		$pks 	= $this->input->post->get('cid', array(), 'array');
		$order 	= $this->input->post->get('order', array(), 'array');

		// Sanitize the input
		$pks = array_map('intval', $pks);
		$order = array_map('intval', $order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
			echo "1";

		// Close the application
		JFactory::getApplication()->close();
	}
	
	/**
	 * Save the manual order inputs from the memberships list page.
	 *
	 * @return	void
	 * @since	1.6
	 */
	 
	public function saveorder()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get the arrays from the Request
		$order	= JFactory::getApplication()->input->get('order', array(), 'array');
		$cids	= JFactory::getApplication()->input->get('cid',	array(), 'array');

		parent::saveorder();
	}

	// Membership - Copy
	public function duplicate()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);

		// Get the selected items
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		// Force array elements to be integers
		$cid = array_map('intval', $cid);

		$query->select($db->qn('id'))->from($db->qn('#__rsmembership_memberships'))->where($db->qn('id').' IN ('.RSMembershipHelper::quoteImplode($cid).')');
		$db->setQuery($query);
		$memberships = $db->loadObjectList();

		// Get the model
		$model = $this->getModel('memberships');

		if (!empty($memberships))
		{
			foreach ($memberships as $membership)
				$model->duplicate($membership->id);
			
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=memberships', false), JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_COPIED_OK'));
		}
		else
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=memberships', false), JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_COPIED_ERROR'));
	}
}