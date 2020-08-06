<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function submitbutton()
{
	var table = window.parent.document.getElementById('<?php echo $this->function; ?>');
	if (typeof(table)=='undefined' || table===null)
		return false;

	$('membership_save_button').disabled = true;
	
	<?php if ($this->what == 'membership_id') { ?>
	rsmembership_submit_form_ajax('share.addMembershipSharedContent');
	<?php } else { ?>
	rsmembership_submit_form_ajax('share.addExtraValueSharedContent');
	<?php } ?>
	
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

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&layout=plugin&tmpl=component&'.$this->what.'='.$this->id); ?>" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
	<button type="button" class="btn btn-small" onclick="document.location = '<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&'.$this->what.'='.$this->id.'&tmpl=component'); ?>'"><?php echo JText::_('Back'); ?></button>
	<button id="membership_save_button" class="btn btn-info btn-small" type="button" onclick="if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::sprintf('COM_RSMEMBERSHIP_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST_TO', JText::_('COM_RSMEMBERSHIP_ADD')); ?>');}else{submitbutton()}"><?php echo JText::_('COM_RSMEMBERSHIP_ADD_SELECTED_ITEMS'); ?></button>
	<br /><br />
	<table class="adminform table table-striped">
		<tr>
			<td width="100%">
			<?php echo JText::_( 'SEARCH' ); ?>
			<input type="text" name="search" id="search" class="text_area input input-normal" value="<?php echo $this->escape($this->filter_word); ?>" onChange="document.adminForm.submit();" />
			<button onclick="this.form.submit();" class="btn btn-medium"><i class="icon-search"></i></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();" class="btn btn-warning btn-medium"><i class="icon-remove"></i></button>
			</td>
			<td nowrap="nowrap"></td>
		</tr>
	</table>
	<div id="editcell1">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_( '#' ); ?></th>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);"/></th>
				<?php foreach ($this->headers as $header) { ?>
					<th <?php echo $header['attributes']; ?>><?php echo $header['innerHTML']; ?></th>
				<?php } ?>
			</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->items);
	if ( !empty( $this->items ) ) { 
		foreach ( $this->items as $row ) 
		{
	?>
			<tr class="row<?php echo $k; ?>">
				<td><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
				<?php foreach ($row as $key => $value) { ?>
				<td><?php echo $value; ?></td>
				<?php } ?>
			</tr>
	<?php
			$i++;
			$k=1-$k;
		}
	}
	?>
			<tr><td colspan="5" align="center" class="center"><?php echo $this->pagination->getListFooter(); ?></td></tr>
		</table>
	</div>
	
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />

	<input type="hidden" name="<?php echo $this->what; ?>" value="<?php echo $this->id; ?>" />
	<input type="hidden" name="share_type" value="<?php echo $this->escape($this->share_type); ?>" />
	<input type="hidden" name="task" value="" />

	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>

<?php
//keep session alive while editing
JHtml::_('behavior.keepalive');
?>