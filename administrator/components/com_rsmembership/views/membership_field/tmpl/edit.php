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
<script type="text/javascript">
	jQuery(document).ready(function(){
		var hide_elements = jQuery('#jform_required0, #jform_rule, #jform_validation').parents('li, div.control-group');

		if ( jQuery("#jform_type option:selected").val() == 'freetext') hide_elements.hide();
		
		var datetimeformat = jQuery('#jform_datetimeformat').parents('li, div.control-group');
		datetimeformat.hide();
		
		if (jQuery("#jform_type option:selected").val() == 'calendar') {
			datetimeformat.show();
		}
		jQuery("#jform_type").change(function(){
			if ( jQuery("#jform_type option:selected").val() == 'freetext') {
				hide_elements.hide();
			} else { 
				hide_elements.show();
			}
			
			if (jQuery("#jform_type option:selected").val() == 'calendar') {
				datetimeformat.show();
			} else {
				datetimeformat.hide();
			}

		});
	});
</script>
<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=membership_field.edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
<?php
$this->fields 	= $this->form->getFieldset('main');

$this->field->startFieldset(JText::_($this->fieldsets['main']->label), 'adminform form');

foreach ($this->fields as $field) {
	if (strtolower($field->type) == 'editor') echo '<div class="clr"></div>';
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