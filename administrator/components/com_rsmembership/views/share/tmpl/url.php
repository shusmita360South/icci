<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');
?>

<script type="text/javascript">
function submitbutton()
{
	var container = window.parent.document.getElementById('<?php echo $this->function; ?>');

	if (typeof(container)=='undefined' || container===null)
		return false;

	// do field validation
	if (document.getElementById('jform_params').value.length == 0)
		alert('<?php echo JText::_('COM_RSMEMBERSHIP_URL_ERROR', true); ?>');
	else
	{
		$('membership_save_button').disabled = true;
		$('membership_save_button2').disabled = true;

		<?php if ($this->what == 'membership_id') { ?>
		rsmembership_submit_form_ajax('share_url.addmembershipurl');
		<?php } else { ?>
		rsmembership_submit_form_ajax('share_url.addextravalueurl');
		<?php } ?>

	}
	
	return false;
}

// submit the form through ajax
function rsmembership_submit_form_ajax(pressbutton)
{
	if (pressbutton) {
		document.adminForm.task.value = pressbutton;
	}
	
	var url = document.adminForm.action;
	var params = [];
	
	for (var i=0; i<document.adminForm.elements.length; i++)
	{
		// don't send an empty value
		if (!document.adminForm.elements[i].name) continue;
		if (document.adminForm.elements[i].name.length == 0) continue;
		// check if the checkbox is checked
		if (document.adminForm.elements[i].type == 'checkbox' && document.adminForm.elements[i].checked == false) continue;
		// check if the radio is selected
		if (document.adminForm.elements[i].type == 'radio' && document.adminForm.elements[i].checked == false) continue;
		
		params.push(document.adminForm.elements[i].name + '=' + escape(document.adminForm.elements[i].value));
	}

	params = params.join('&');

	jQuery.ajax({
		url: url,
		type: 'POST',
		data: params,
		dataType: 'html'
	})
	.done(function( response ) {
		window.parent.location.reload(true);
	});
}
</script>

<div id="<?php echo $this->function; ?>" class="row-fluid">
	<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=url&tmpl=component&'.$this->what.'='.$this->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

	<button type="button" class="btn btn-small pull-left" onclick="document.location = '<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&'.$this->what.'='.$this->id.'&tmpl=component'); ?>'"><?php echo JText::_('Back'); ?></button>
	<button id="membership_save_button" class="btn btn-small btn-info pull-left" type="button" onclick="submitbutton();"><?php echo $this->item->id > 0 ? JText::_('COM_RSMEMBERSHIP_UPDATE_URL') : JText::_('COM_RSMEMBERSHIP_ADD_URL'); ?></button>
		<div class="clearfix"></div><br />

		<?php
		$this->fields 	= $this->form->getFieldset('main');

		$this->field->startFieldset(JText::_($this->fieldsets['main']->label), 'adminform form');

		foreach ($this->fields as $field) 
		{
			$this->field->showField( $field->hidden ? '' : $field->label, $field->name == 'jform[params]' ? '<span id="rsme_url_addr">index.php?option=</span>'.$field->input : $field->input);
		}

		$this->field->endFieldset();
		?>

	<button id="membership_save_button2" class="btn btn-small btn-info pull-left" type="button" onclick="submitbutton();"><?php echo $this->item->id > 0 ? JText::_('COM_RSMEMBERSHIP_UPDATE_URL') : JText::_('COM_RSMEMBERSHIP_ADD_URL'); ?></button>

	<?php echo JHtml::_( 'form.token' ); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="<?php echo $this->what; ?>" value="<?php echo $this->id; ?>" />

	<input type="hidden" name="filter_order" value="ordering" />
	<input type="hidden" name="filter_order_Dir" value="ASC" />
</form>
</div>
<?php
//keep session alive while editing
JHtml::_('behavior.keepalive');
?>