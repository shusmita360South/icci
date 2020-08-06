<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class JFormFieldPeriods extends JFormField {
	protected $type = 'Periods';

	public function getInput() 
	{
		$period_types  = array(
			JHtml::_('select.option', 'h', JText::_('COM_RSMEMBERSHIP_HOURS')),
			JHtml::_('select.option', 'd', JText::_('COM_RSMEMBERSHIP_DAYS')),
			JHtml::_('select.option', 'm', JText::_('COM_RSMEMBERSHIP_MONTHS')),
			JHtml::_('select.option', 'y', JText::_('COM_RSMEMBERSHIP_YEARS'))
		);

		if ( !is_array($this->value) ) {
			$this->value = array('d', '');
		}
		// set the default length to days
		elseif (is_null($this->value[0])) {
			$this->value[0] = 'd';
		}

		return JHtml::_('select.genericlist', $period_types, $this->name.'[]', ' class="'.$this->element['class'].'" ', 'value', 'text', $this->value[0], $this->id.'0').'<input type="text" name="'.$this->name.'[]'.'" id="'.$this->id.'1" value="'.$this->value[1].'" class="input-tiny" />';
	}
}