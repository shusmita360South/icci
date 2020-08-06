<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipModelConfiguration extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_rsmembership.configuration', 'configuration', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData() {
		$data = (array) RSMembershipHelper::getConfig();

		return $data;
	}
	
	public function getSideBar() {
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		
		return RSMembershipToolbarHelper::render();
	}
	
	public function getRSFieldset() {
		require_once JPATH_COMPONENT.'/helpers/adapters/fieldset.php';
		
		$fieldset = new RSFieldset();
		return $fieldset;
	}
	
	public function getRSTabs() {
		require_once JPATH_COMPONENT.'/helpers/adapters/tabs.php';
		
		$tabs = new RSTabs('com-rsmembership-configuration');
		return $tabs;
	}
	
	public function save($data) {
		$config = RSMembershipConfig::getInstance();
		
		if (!isset($data['captcha_enabled_for']) && isset($data['captcha_enabled']) && $data['captcha_enabled'] > 0) 
			$data['captcha_enabled_for'] = array();

		foreach ($data as $prop => $val) {
            $config->set($prop, $val);
        }

		RSMembershipHelper::readConfig(true);

		return true;
	}

	public function idevCheckConnection()
	{
		$data  = JFactory::getApplication()->input->post->get('jform', array(), 'array');

		if (!empty($data['idev_url']))
		{
			$idev_url = $data['idev_url'];
			if (strlen($idev_url) > 5)
			{
				$idev_url = rtrim($idev_url, '/');
				$idev_url.= '/';
			}

			$config = RSMembershipConfig::getInstance();
			$config->set('idev_url', $idev_url);
		}

		$result = RSMembership::updateIdev(array('idev_saleamt' => 1.00, 'idev_ordernum' => 'test', 'ip_address' => '127.0.0.1'));

		if (!$result['success'])
		{
			JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_RSMEMBERSHIP_IDEV_COULD_NOT_CONNECT', $result['url'], !empty($result['error']) ? $result['error'] : JText::_('COM_RSMEMBERSHIP_UNKNOWN'), $result['code']), 'warning');
			return false;
		}
		
		return true;
	}
}