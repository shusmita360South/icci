<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('text');

class JFormFieldDate extends JFormFieldText {
	protected $type = 'Date';

	public function getInput() {
		$db  = JFactory::getDbo();

		// jQuery UI JS
		JHtml::_('script', 'com_rsmembership/admin/ui/core.js', array('relative' => true, 'version' => 'auto'));
		JHtml::_('script', 'com_rsmembership/admin/ui/widget.js', array('relative' => true, 'version' => 'auto'));
		JHtml::_('script', 'com_rsmembership/admin/ui/mouse.js', array('relative' => true, 'version' => 'auto'));
		JHtml::_('script', 'com_rsmembership/admin/ui/slider.js', array('relative' => true, 'version' => 'auto'));
		JHtml::_('script', 'com_rsmembership/admin/ui/datepicker.js', array('relative' => true, 'version' => 'auto'));
		JHtml::_('script', 'com_rsmembership/admin/ui/timepicker.js', array('relative' => true, 'version' => 'auto'));


		// & CSS
		JHtml::_('stylesheet', 'com_rsmembership/admin/ui/jquery.ui.all.css', array('relative' => true, 'version' => 'auto'));
		JHtml::_('stylesheet', 'com_rsmembership/admin/ui/jquery.ui.timepicker.css', array('relative' => true, 'version' => 'auto'));

		// Initialize
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration("jQuery(document).ready(function($){
			$('#".$this->id."').datetimepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'HH:mm:ss'
			});
		
			$('#".$this->id."_img').click(function(){
				$('#".$this->id."').datetimepicker('show');
			});

		});");
		
		if ($this->value == $db->getNullDate()) {
			$this->value = '';
		} else {
			$this->value = JHtml::_('date', $this->value, 'Y-m-d H:i:s');
		}
		
		$html[] = '<div class="input-append">';
		$html[] = parent::getInput();
		$html[] = '<span id="'.$this->id.'_img" class="add-on rsme_pointer"><i class="icon-calendar"></i></span>';
		$html[] = '</div>';
		return implode("\n", $html);
	}
}