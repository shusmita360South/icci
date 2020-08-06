<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class JFormFieldMemberships extends JFormField {
	protected $type = 'Memberships';

	public function getInput() 
	{
		$options 	= array();
		$multiple 	= ($this->element['multiple'] ? 'multiple="multiple"' : '');
		$size		= ($this->element['size'] ? 'size="'.$this->element['size'].'"' : '');
		$onchange	= ($this->element['onchange'] ? 'onchange="'.$this->element['onchange'].'"' : '');

		$all_membs  = RSMembershipHelper::getMembershipsList();
		$options = array_merge($options, $all_membs);

		return JHtml::_('select.genericlist', $options, $this->name, 'class="'.$this->element['class'].'" '.$onchange.' '.$multiple.' '.$size, 'value', 'text', $this->value, $this->id);
	}
}
		