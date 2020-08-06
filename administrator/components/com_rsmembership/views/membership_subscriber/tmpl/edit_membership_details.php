<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>
<?php
$this->field->startFieldset(JText::_($this->fieldsets['main']->label), 'adminform form');

foreach ($this->form->getFieldset('main') as $field) {
	if (strtolower($field->type) == 'editor') {
		echo '<div class="clr"></div>';
	}

	$this->field->showField( $field->hidden ? '' : $field->label, $field->input);
}

$this->field->endFieldset();

?>

