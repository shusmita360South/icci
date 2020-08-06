<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class JFormFieldIdevurl extends JFormField {
	protected $type = 'Idevurl';

	public function getInput() 
	{
		return '
		<input type="text" name="'.$this->name.'" value="'.htmlspecialchars($this->value, ENT_QUOTES, 'utf-8').'" class="'.$this->element['class'].'" id="jform_idev_url" size="100" />
		<button type="button" class="fltlft btn btn-info btn-tiny" onclick="rsm_idev_check_connection();">'.JText::_('COM_RSMEMBERSHIP_IDEV_CHECK_CONNECTION').'</button>';
	}
}