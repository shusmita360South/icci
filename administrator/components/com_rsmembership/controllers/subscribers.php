<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerSubscribers extends JControllerAdmin
{
	protected $text_prefix = 'COM_RSMEMBERSHIP';

	public function getModel($name = 'Subscriber', $prefix = 'RSMembershipModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function writecsv() {
		$model = $this->getModel('Subscribers');
		$input = JFactory::getApplication()->input;
		
		try {
			$response = $model->writeCSV($input->getInt('start'), $input->get('filehash'));
			
			$this->showResponse(true, $response);
		} catch (Exception $e) {
			$this->showResponse(false, $e->getMessage());
		}
	}
	
	protected function showResponse($success, $data=null) {
		$app 		= JFactory::getApplication();
		$document 	= JFactory::getDocument();
		
		// set JSON encoding
		$document->setMimeEncoding('application/json');
		
		// compute the response
		$response = new stdClass();
		$response->success = $success;
		if ($data) {
			$response->response = $data;
		}
		
		// show the response
		echo json_encode($response);
		
		// close
		$app->close();
	}
	
	public function exportcsv() {
		require_once JPATH_COMPONENT.'/helpers/export.php';
		
		$model = $this->getModel('Subscribers');
		
		$filename = JText::_('COM_RSMEMBERSHIP_SUBSCRIBERS');
		RSMembershipExport::buildCSVHeaders($filename);
		
		$input = JFactory::getApplication()->input;
		$fileHash = $input->get('filehash');
		
		echo RSMembershipExport::getCSV($fileHash);
		
		JFactory::getApplication()->close();
	}
	
}