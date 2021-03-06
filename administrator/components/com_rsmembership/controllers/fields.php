<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

class RSMembershipControllerFields extends JControllerAdmin
{
	protected $text_prefix = 'COM_RSMEMBERSHIP';

	public function __construct($config = array()) 
	{
		parent::__construct($config);
		
		$this->registerTask('showinsubscribers', 'changevalue');
		$this->registerTask('hideinsubscribers', 'changevalue');
		
		$this->registerTask('setrequired', 'changevalue');
		$this->registerTask('unsetrequired', 'changevalue');
	}

	public function getModel($name = 'Field', $prefix = 'RSMembershipModel', $config = array('ignore_request' => true))
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
	
	public function changevalue()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to change the values from the request.
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		$data = array('showinsubscribers' => 1, 'hideinsubscribers' => 0, 'setrequired' => 1, 'unsetrequired' => 0);
		$task = $this->getTask();
		$value = ArrayHelper::getValue($data, $task, 0, 'int');

		
		if (empty($cid))
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			$cid = array_map('intval', $cid);

			// Change value of the items.
			try
			{
				$model->changevalue($cid, $value, $task);

				if ($value == 1)
				{
					$ntext = $this->text_prefix . '_N_ITEMS_'.strtoupper($task);
				}
				elseif ($value == 0)
				{
					$ntext = $this->text_prefix . '_N_ITEMS_'.strtoupper($task);
				}
				
				$this->setMessage(JText::plural($ntext, count($cid)));
			}
			catch (Exception $e)
			{
				$this->setMessage(JText::_('JLIB_DATABASE_ERROR_ANCESTOR_NODES_LOWER_STATE'), 'error');
			}

		}
		
		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}
}