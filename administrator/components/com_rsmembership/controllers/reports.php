<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerReports extends JControllerForm
{
	public function getdata()
	{
		$jinput   = JFactory::getApplication()->input;
		$filters  = $jinput->get('jform', array(), 'array');
		
		$model 	  = $this->getModel('Reports', 'RSMembershipModel');
		$response = $model->getReportData($filters);

		echo json_encode($response);
		exit;
	}
}