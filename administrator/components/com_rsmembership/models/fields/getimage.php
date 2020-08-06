<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

// Thumb Image type  used in membership and file

class JFormFieldGetImage extends JFormField {
	protected $type = 'GetImage';

	public function getInput() 
	{
		$input  = '<div style="float:left">';
		$folder = (isset($this->element['folder_location']) ? $this->element['folder_location'].'/' : '');

		if ( !empty($this->value) ) {
			$input .= JHtml::image('components/com_rsmembership/assets/thumbs/'.$folder.$this->value, '').
			'<div class="clr"></div><input type="checkbox" value="1" name="jform[thumb_delete]" /> '.JText::_('COM_RSMEMBERSHIP_DELETE_THUMB').'<br />';
		}

		$input .= '<input type="file" name="'.$this->name.'" id="'.$this->id.'" value="" /></div>';

		return $input;
	}
}