<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=extra.edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
<?php
$this->fields 	= $this->form->getFieldset('main');

$this->field->startFieldset(JText::_($this->fieldsets['main']->label), 'adminform form');

foreach ($this->fields as $field) 
{
	if (strtolower($field->type) == 'editor' || $field->name == 'jform[assign_extras]') echo '<div class="clr"></div>';

	if ($field->name == 'jform[assign_extras]') 
	{
		if ($this->item->id) 
			$input = '<a class="button btn btn-info" href="'.JRoute::_('index.php?option=com_rsmembership&view=extravalues&extra_id='.$this->item->id).'">'.JText::_('COM_RSMEMBERSHIP_EXTRA_VALUES_ASSIGN').'</a>';
		else 
			$input = '<strong class="rsmembership_critical">'.JText::_('COM_RSMEMBERSHIP_EXTRA_SAVE_FIRST').'</strong>';

		$this->field->showField( $field->label, $input );
	} else 
		$this->field->showField( $field->hidden ? '' : $field->label, $field->input);
}
$this->field->endFieldset();
?>

<?php echo JHtml::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />

</form>

<?php
//keep session alive while editing
JHtml::_('behavior.keepalive');
?>