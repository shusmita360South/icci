<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

// set description if required
if (isset($this->fieldset->description) && !empty($this->fieldset->description)) { ?>
	<div class="com-rsmembership-tooltip"><?php echo JText::_($this->fieldset->description); ?></div>
<?php } ?>
<?php
$this->field->startFieldset(JText::_($this->fieldset->label), 'rs_fieldset adminform');

$this->fields 	= $this->form->getFieldset('main');
?>
<div class="row-fluid">
	<div class="span6">
<?php
foreach ($this->fields as $field) 
{
	if (strtolower($field->type) == 'spacer') 
	{
		if ( $field->name == 'jform[one_time_price_settings]' ) // we start the second half and divide each spacer field into div.well
			echo '</div><!-- end span6 --> <div class="span6"><div class="well well-small">';
		elseif ( $field->name == 'jform[basic_info]' ) // we leave the Basic info spacer alone
			echo '';
		else 
			echo '</div> <!-- end well --> <div class="well well-small">';

		echo '<div class="page-header"><h3>'.JText::_($field->label).'</h3></div>';

		if ($field->description) 
			echo '<div class="com-rsmembership-spacer-desc">'.JText::_($field->description).'</div>';
	} else
		$this->field->showField($field->hidden ? '' : $field->label, $field->input);
}
?>
		</div><!-- end well -->
	</div><!-- end span6 -->
</div><!-- end row-fluid -->
<?php
$this->field->endFieldset();