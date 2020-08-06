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

$this->fields 	= $this->form->getFieldset('sharde_content');
foreach ($this->fields as $field) 
{
	$this->field->showField($field->hidden ? '' : $field->label, $field->input);
}
?>
<?php if (!empty($this->item->id)) { ?>
	<div class="button2-left">
		<div class="blank">
			<a class="btn btn-info btn-small" data-toggle="modal" role="button" title="Select the path" href="#addmembershipsharedcontent"><?php echo JText::_('COM_RSMEMBERSHIP_ADD_CONTENT'); ?></a>
		</div>
	</div>
	<span class="rsmembership_clear" style="margin-bottom: 10px;"></span>

	<?php echo $this->loadTemplate('shared_list'); ?>

	<?php
	echo JHtml::_('bootstrap.renderModal', 'addmembershipsharedcontent', array(
		'title' => JText::_('COM_RSMEMBERSHIP_ADD_CONTENT'),
		'url' => JRoute::_('index.php?option=com_rsmembership&view=share&extra_value_id='.$this->item->id.'&tmpl=component'),
		'height' => '475',
		'width' => '660',
	));
	?>
<?php } else { ?>
	<?php echo JText::_('COM_RSMEMBERSHIP_SHARED_SAVE_FIRST'); ?>
<?php } ?>
<div class="clearfix clr"></div>
<?php
$this->fields 	= $this->form->getFieldset('shared');
foreach ($this->fields as $field) {
	$this->field->showField($field->hidden ? '' : $field->label, $field->input);
}
?>
<?php 
$this->field->endFieldset();
?>