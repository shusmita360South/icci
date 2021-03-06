<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerExtraValue extends JControllerForm
{
	public function __construct() 
	{
		parent::__construct();

		$this->registerTask('orderup', 	'foldersmove');
		$this->registerTask('orderdown', 'foldersmove');
		$this->registerTask('saveorder', 'folderssaveorder');

		$this->registerTask('folderspublish', 	'folderschangestatus');
		$this->registerTask('foldersunpublish', 'folderschangestatus');
	}
	
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$append 	= parent::getRedirectToItemAppend($recordId, $urlVar);
		$extra_id 	= JFactory::getApplication()->input->get('extra_id', 0, 'int');

		if ($extra_id) 
			$append .= '&extra_id=' . $extra_id;

		return $append;
	}

	protected function getRedirectToListAppend()
	{
		$append 	= parent::getRedirectToListAppend();
		$extra_id 	= JFactory::getApplication()->input->get('extra_id', 0, 'int');

		if ($extra_id) 
			$append .= '&extra_id=' . $extra_id;

		return $append;
	}

	public function foldersSaveOrder()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		$jinput = JFactory::getApplication()->input;

		$model 	= $this->getModel('ExtraValue', 'RSMembershipModel');
		// get the table instance
		$row 	= $model->getTable('ExtraValueShared','RSMembershipTable');

		// Get the selected items
		$cid = $jinput->get('cid_folders', array(), 'array');

		// Get the ordering
		$order = $jinput->get('order', array(0), 'array');

		// Force array elements to be integers
		$cid = array_map('intval', $cid);
		$order = array_map('intval', $order);
		
		// Load each element of the array
		for ($i=0;$i<count($cid);$i++)
		{
			// Load the item
			$row->load($cid[$i]);
			
			// Set the new ordering only if different
			if ($row->ordering != $order[$i])
			{	
				$row->ordering = $order[$i];
				if (!$row->store()) 
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		$tabposition = $jinput->get('tabposition', 0, 'int');
		// Redirect
		if (!empty($row->extra_value_id)) 
			 $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=extravalue.edit&id='.$row->extra_value_id.'&tabposition='.$tabposition, false),  JText::_('COM_RSMEMBERSHIP_EXTA_VALUE_FILES_ORDERED'));
		else
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=memberships', false));
	}
	
	// Folder - Move Up/Down
	public function foldersmove() 
	{
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		$db 	= JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		$model 	= $this->getModel('ExtraValue', 'RSMembershipModel');
		$row 	= $model->getTable('ExtraValueShared','RSMembershipTable');

		// Get the selected items
		$cid  = $jinput->get('cid_folders', array(), 'array');

		// Get the task
		$task = $jinput->get('task', '', 'cmd');

		// Force array elements to be integers
		$cid = array_map('intval', $cid);

		// Set the direction to move
		$direction = $task == 'orderup' ? -1 : 1;

		// Can move only one element
		if (is_array($cid))	$cid = $cid[0];

		// Load row
		if (!$row->load($cid)) {
			$this->setError($row->getError());
			return false;
		}

		// Move
		$row->move($direction, $db->qn('extra_value_id').' = '.$db->q($row->extra_value_id));

		$tabposition = $jinput->get('tabposition', 0, 'int');
		// Redirect
		if (!empty($row->extra_value_id)) 
			 $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=extravalue.edit&id='.$row->extra_value_id.'&tabposition='.$tabposition, false));
		else
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=extras', false));
	}
	
	// Folder - Publish/Unpublish
	public function foldersChangestatus()
	{
		$jinput = JFactory::getApplication()->input;
		// Check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');
		$model 	= $this->getModel('ExtraValue', 'RSMembershipModel');
		$row 	= $model->getTable('ExtraValueShared','RSMembershipTable');
		$msg 	= '';
		// Get the selected items
		$cid = $jinput->get('cid_folders', array(), 'array');

		// Get the task
		$task = $jinput->get('task', '', 'cmd');
		
		// Force array elements to be integers
		$cid = array_map('intval', $cid);

		// No items are selected
		if (!is_array($cid) || count($cid) < 1) {
			JFactory::getApplication()->enqueueMessage(JText::_('SELECT ITEM PUBLISH'), 'warning');
		}
		// Try to publish the item
		else
		{
			$value = $task == 'folderspublish' ? 1 : 0;
			if (!$model->foldersPublish($cid, $value)) {
				throw new Exception($model->getError(), 403);
			}

			$total = count($cid);
			if ($value)
				$msg = JText::plural('COM_RSMEMBERSHIP_N_ITEMS_PUBLISHED_1', $total);
			else
				$msg = JText::plural('COM_RSMEMBERSHIP_N_ITEMS_UNPUBLISHED_1', $total);

			// Clean the cache, if any
			$cache = JFactory::getCache('com_rsmembership');
			$cache->clean();
		}

		$row->load($cid[0]);

		$tabposition = $jinput->get('tabposition', 0, 'int');
		// Redirect
		if (!empty($row->extra_value_id)) 
			 $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=extravalue.edit&id='.$row->extra_value_id.'&tabposition='.$tabposition, false), $msg);
		else
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=memberships', false));
	}

	// Folder - Remove
	public function foldersRemove()
	{
		$jinput = JFactory::getApplication()->input;
		// Check for request forgeries
		$token_type = $jinput->get('token_type', 'get', 'string');
		JSession::checkToken($token_type) or jexit('Invalid Token');

		// Check for request forgeries
		$model 		= $this->getModel('ExtraValue', 'RSMembershipModel');
		$row 		= $model->getTable('ExtraValueShared','RSMembershipTable');
		$extra_id 	= $jinput->get('extra_id', 0, 'int');
		// Get the selected items
		$cid = $jinput->get('cid_folders', array(), 'array');

		// Force array elements to be integers
		$cid = array_map('intval', $cid);

		$msg = '';

		// No items are selected
		if (!is_array($cid) || count($cid) < 1) {
			JFactory::getApplication()->enqueueMessage(JText::_('SELECT ITEM DELETE'), 'warning');
		}
		// Try to remove the item
		else
		{
			$row->load($cid[0]);

			if (!empty($row->extra_value_id))
			{
				$model->foldersRemove($cid);

				$total = count($cid);
				$msg = JText::sprintf('COM_RSMEMBERSHIP_EXTRA_VALUE_FILES_DELETED', $total);

				// Clean the cache, if any
				$cache = JFactory::getCache('com_rsmembership');
				$cache->clean();
			}
		}

		$tabposition = $jinput->get('tabposition', 0, 'int');
		// Redirect
		if (!empty($row->extra_value_id)) 
			 $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=extravalue.edit&id='.$row->extra_value_id.'&extra_id='.$extra_id.'&tabposition='.$tabposition, false), $msg);
		else
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=memberships', false));
	}
	
	/*
	Joomla 3.0 Saving order function for shared content
	*/
	public function saveOrderAjax()
	{
		$return = true;
		$db 	= JFactory::getDBO();
		$model 	= $this->getModel('Extravalue', 'RSMembershipModel');
		$row 	= $model->getTable('ExtravalueShared','RSMembershipTable');
		$jinput = JFactory::getApplication()->input;
		$pks 	= $jinput->get('cid_folders', array(), 'array');
		$order 	= $jinput->get('order', array(), 'array');

		// Sanitize the input
		$pks = array_map('intval', $pks);
		$order = array_map('intval', $order);

		// Save the ordering
		for ($i=0;$i<count($pks);$i++)
		{
			// Load the item
			$row->load($pks[$i]);

			// Set the new ordering only if different
			if ($row->ordering != $order[$i])
			{	
				$row->ordering = $order[$i];
				if (!$row->store()) 
				{
					$this->setError($db->getErrorMsg());
					$return = false;
				}
			}
		}

		if ($return)
			echo "1";

		// Close the application
		JFactory::getApplication()->close();
	}
}