<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class JFormFieldResize extends JFormField {
	protected $type = 'Resize';

	public function getInput() 
	{
		$input = '<div class="rsmembership_resize"><input type="checkbox" value="1" name="'.$this->name.'" /> <span>'. JText::_('COM_RSMEMBERSHIP_RESIZE_TO').' </span> 
		<input type="text" name="jform[thumb_w]" value="'.$this->value.'" id="'.$this->id.'" size="10" maxlength="255" /> <span>'.JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_PX').'</span></div>';

		return $input;
	}
}