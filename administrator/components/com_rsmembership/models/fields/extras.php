<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('checkboxes');

class JFormFieldExtras extends JFormFieldCheckboxes {
	protected $type = 'Extras';
	public $hasValues = false;

	protected function getOptions() {
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		$options = array();
		
		$query->select('*')
			  ->from($db->qn('#__rsmembership_extras'))
			  ->order($db->qn('ordering').' '.$db->escape('asc'));
		$db->setQuery($query);
		$extras = $db->loadObjectList();
		
		foreach ($extras as $extra) {
			$tmp = JHtml::_('select.option', $extra->id, $extra->name);
			
			$tmp->checked = false;
			if ($this->value && is_array($this->value) && in_array($extra->id, $this->value)) {
				$tmp->checked = true;
			}
			
			// Add the option object to the result set.
			$options[] = $tmp;
		}
		
		if ($options) {
			$this->hasValues = true;
		}
		
		reset($options);
		
		return $options;
	}
}