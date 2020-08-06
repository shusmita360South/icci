<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class JFormFieldStatuses extends JFormField {
	protected $type = 'Statuses';

	public function getInput() 
	{
		$multiple 	  = ($this->element['multiple'] ? 'multiple="multiple"' : '');
		$size		  = ($this->element['size'] ? 'size="'.$this->element['size'].'"' : '');
		$all_statuses = RSMembershipHelper::getStatusesList();

		return JHtml::_('select.genericlist', $all_statuses, $this->name, 'class="'.$this->element['class'].'" '.$multiple.' '.$size, 'value', 'text', $this->value);
	}
}
		