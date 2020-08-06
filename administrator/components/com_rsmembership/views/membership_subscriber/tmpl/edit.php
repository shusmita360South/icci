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
JHtml::_('behavior.tabstate');

$session = JFactory::getSession();
$is_edited = $session->get('com_rsmembership.membership_subscriber_edit', 0);
//unset the edit session
$session->set('com_rsmembership.membership_subscriber_edit', 0);

?>
<script type="text/javascript">
// refresh the results from the parent window
<?php if ($is_edited) { ?>
if (window.parent) {
	setTimeout(function(){
		window.parent.location.reload(true);
	}, 2000);
}
<?php } ?>

function rsmembership_change_unlimited()
{
	document.getElementById('jform_membership_end').disabled = document.getElementById('jform_unlimited').checked == true;
}

function rsmembership_change_membership()
{
	var url = 'index.php?option=com_rsmembership';
	var params = [
		'option=com_rsmembership',
		'task=ajax.date',
		'format=raw',
		'membership_id=' + encodeURIComponent(jQuery('#jform_membership_id').val()),
		'membership_start=' + encodeURIComponent(jQuery('#jform_membership_start').val())
	];


	jQuery.ajax({
		url: url,
		type: 'POST',
		data: params.join('&'),
		dataType: 'html'
	})
	.done(function( response ) {
		if (response == '<?php echo $this->escape(JFactory::getDbo()->getNullDate()); ?>') {
			document.getElementById('jform_unlimited').checked = true;
		} else {
			document.getElementById('jform_membership_end').value = response;
			document.getElementById('jform_unlimited').checked = false;
		}
		rsmembership_change_unlimited();
		rsmembership_calculate_price();
	});
}

function rsmembership_calculate_price() {
	<?php if (!$this->item->id) { ?>
		var memberships = {};
		var extras		= {};
		<?php foreach ($this->prices['memberships'] as $membership_id => $price) { ?>
			memberships[<?php echo (int) $membership_id; ?>] = <?php echo $this->jsEscape($price); ?>;
		<?php } ?>
		<?php foreach ($this->prices['extras'] as $extra_id => $price) { ?>
			extras[<?php echo (int) $extra_id; ?>] = <?php echo $this->jsEscape($price); ?>;
		<?php } ?>
		var total = 0;
		total += memberships[jQuery('#jform_membership_id').val()];
		for (var i=0; i < document.getElementById('jform_extras').options.length; i++) {
			var option = document.getElementById('jform_extras').options[i];
			if (option.selected) {
				total += extras[option.value];
			}
		}
		jQuery('#jform_price').val(total);
	<?php } ?>
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&user_id=' . $this->item->user_id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form form-validate form-horizontal rsm_subscriber_membership_form">
<div class="pull-right hide">
	<button type="button" class="btn btn-success" id="membership_save_button" onclick="Joomla.submitbutton('membership_subscriber.apply')"><?php echo JText::_('COM_RSMEMBERSHIP_SAVE');?></button>
</div>
	<span class="rsmembership_clear"></span>
<?php
	$this->tabs->addTitle(JText::_($this->fieldsets['main']->label), 'membership-details');
	// load content
	$tmembership_details_content = $this->loadTemplate('membership_details');
	// add the tab content
	$this->tabs->addContent($tmembership_details_content);
	
	$this->tabs->addTitle(JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_INFO'), 'membership-info');
	// load content
	$membership_info_content = $this->loadTemplate('membership_info');
	// add the tab content
	$this->tabs->addContent($membership_info_content);
	
	$this->tabs->render();
?>
<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="last_transaction_id" value="<?php echo $this->item->last_transaction_id; ?>" />
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
</form>
<script type="text/javascript">
rsmembership_change_unlimited();
<?php if (!$this->item->id) { ?>
rsmembership_change_membership();
rsmembership_calculate_price();
<?php } ?>
</script>