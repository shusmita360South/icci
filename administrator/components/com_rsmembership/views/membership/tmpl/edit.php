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
?>
<style>
	#toolbar-delete,
	#toolbar-publish,
	#toolbar-unpublish {
		display:none;
	}

 	#com-rsmembership-accordion-membership .modal-body {
	 	max-height: initial;
 	}

</style>
<script type="text/javascript">
	function rsm_fixed_expiry(elem) {
		var use_trial = jQuery('#jform_use_trial_period1').is(':checked');

		if (elem.is(':checked')) 
		{
			jQuery('#jform_period_values0, #jform_period_values1').prop('disabled', true);

			if (use_trial) 
				jQuery('#jform_trial_period_values0, #jform_trial_period_values1').prop('disabled', true);

			jQuery('#jform_fixed_expiry_values0, #jform_fixed_expiry_values1, #jform_fixed_expiry_values2').prop('disabled', false);
		} else {
			jQuery('#jform_period_values0, #jform_period_values1').prop('disabled', false);

			if (use_trial) 
				jQuery('#jform_trial_period_values0, #jform_trial_period_values1').prop('disabled', false);

			jQuery('#jform_fixed_expiry_values0, #jform_fixed_expiry_values1, #jform_fixed_expiry_values2').prop('disabled', true);
		}
	}

	function rsm_disable_renewal_price(elem) {
		if ( elem.val() == 1 ) jQuery('#jform_renewal_price').prop('disabled', false);
		else jQuery('#jform_renewal_price').prop('disabled', true);
	}
	
	function rsm_disable_trial_price_period(elem) {
		var use_fixed = jQuery('#jform_fixed_expiry_values0').is(':checked');

		if ( elem.val() == 1 ) {
			jQuery('#jform_trial_price').prop('disabled', false);
			if (!use_fixed) 
				jQuery('#jform_trial_period_values0, #jform_trial_period_values1').prop('disabled', false);
		} else {
			jQuery('#jform_trial_price').prop('disabled', true);
			if (!use_fixed) 
				jQuery('#jform_trial_period_values0, #jform_trial_period_values1').prop('disabled', true);
		}
	}

	function rsm_display_group_lists(elem) {
		if (elem.val() == 1) 
			jQuery('#jformgid_subscribe, #jformgid_expire').parents('li, .control-group').show();
		else 
			jQuery('#jformgid_subscribe, #jformgid_expire').parents('li, .control-group').hide();
	}
	
	function rsm_disable_email_settings(elem) {
		if (elem.val() == 1) 
			jQuery('#jform_user_email_from, #jform_user_email_from_addr').prop('disabled', true);
		else 
			jQuery('#jform_user_email_from, #jform_user_email_from_addr').prop('disabled', false);
	}
	function rsm_disable_recurring_settings(elem) {
		if (elem.val() == 1) 
			jQuery('#jform_recurring_times').prop('disabled', false);
		else 
			jQuery('#jform_recurring_times').prop('disabled', true);
	}

jQuery(document).ready(function() {
	// disable renewal price input
	jQuery('#jform_use_renewal_price0, #jform_use_renewal_price1').click(function() {
		rsm_disable_renewal_price(jQuery(this));
	});

	// disable trial price and period
	jQuery('#jform_use_trial_period0, #jform_use_trial_period1').click(function() {
		rsm_disable_trial_price_period(jQuery(this));
	});
	
	// fixed period enabled
	jQuery('#jform_fixed_expiry_values3').click( function () { 
		rsm_fixed_expiry(jQuery(this)) 
	});

	// show / hide group lists
	jQuery('#jform_gid_enable0, #jform_gid_enable1').click(function(){
		rsm_display_group_lists(jQuery(this));
	});
	
	// enable / disable From Name - From Email Address fields
	jQuery('#jform_user_email_use_global0, #jform_user_email_use_global1').click(function(){
		rsm_disable_email_settings(jQuery(this));
	});
	
	// enable / disable Recurring payment times
	jQuery('#jform_recurring0, #jform_recurring1').click(function(){
		rsm_disable_recurring_settings(jQuery(this));
	});
	
	// trigger events with loaded values when
	rsm_disable_renewal_price(jQuery('#jform_use_renewal_price input:checked'));
	rsm_disable_trial_price_period(jQuery('#jform_use_trial_period input:checked'));
	rsm_display_group_lists(jQuery('#jform_gid_enable input:checked'));
	rsm_disable_email_settings(jQuery('#jform_user_email_use_global input:checked'));
	rsm_disable_recurring_settings(jQuery('#jform_recurring input:checked'));
	<?php if ($this->item->fixed_expiry == 1) { ?>
		rsm_fixed_expiry(jQuery('#jform_fixed_expiry_values3'));
	<?php } ?>
});
</script>
<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=membership.edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal" enctype="multipart/form-data" >
<?php
	foreach ($this->fieldsets as $name => $fieldset) 
	{
		// add the tab title
		$this->tabs->addTitle($fieldset->label, $fieldset->name);

		// prepare the content
		$this->fieldset = $fieldset;

		$this->fields 	= $this->form->getFieldset($fieldset->name);
		$content = $this->loadTemplate($fieldset->name);

		// add the tab content
		$this->tabs->addContent($content);
	}

	// RSMail! integration
	$this->app->triggerEvent('rsm_onAfterMembershipTabs', array(array('tabs' => $this->tabs)));

	// render tabs
	$this->tabs->render($this->activeTab);
?>

<?php echo JHtml::_( 'form.token' ); ?>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="tabposition" id="tabposition" value="0"/>
</form>

<script type="text/javascript">
	var sharedItems = <?php echo ((!empty($this->item->id) && !empty($this->item->shared)) ? '1' : '0'); ?>;

	jQuery('#adminForm > .tab-content > div').each(function(index, elem){
		if (jQuery(elem).hasClass('active')) {
			jQuery('#tabposition').val(index);
			if (sharedItems && index == 3) {
				jQuery('#toolbar-delete').css('display', 'inline-block');
				jQuery('#toolbar-publish').css('display', 'inline-block');
				jQuery('#toolbar-unpublish').css('display', 'inline-block');
			} else {
				jQuery('#toolbar-delete').hide();
				jQuery('#toolbar-publish').hide();
				jQuery('#toolbar-unpublish').hide();
			}
		}
	});

	jQuery('#adminForm > #com-rsmembership-membership > li').each(function(index, elem){
		jQuery(elem).find('a').on('click', function(){
			jQuery('#tabposition').val(index);
			if (sharedItems && index == 3) {
				jQuery('#toolbar-delete').css('display', 'inline-block');
				jQuery('#toolbar-publish').css('display', 'inline-block');
				jQuery('#toolbar-unpublish').css('display', 'inline-block');
			} else {
				jQuery('#toolbar-delete').hide();
				jQuery('#toolbar-publish').hide();
				jQuery('#toolbar-unpublish').hide();
			}
		})
	});

</script>