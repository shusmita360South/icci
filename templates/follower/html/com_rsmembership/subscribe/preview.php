<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?> 

<div class="page-header-2 light-bg section-padding-top section-padding-bottom-half">
  <div class="grid-container-medium">
    
    <h1><?php echo $this->escape($this->params->get('page_heading', $this->membership->name)); ?></h1>
    
  </div>
</div>


<div class="form-outer section-padding-bottom grey-bg">
  	<div class="grid-container-small  white-bg"> 

		<form method="post" class="rsmembership_form" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=paymentredirect'); ?>" name="membershipForm" id="rsm_subscribe_preview_form">
			<div class="item-page">
				<h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_PURCHASE_INFORMATION'); ?></h3>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
				<tr>
					<td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP'); ?>:</td>
					<td><?php echo $this->escape($this->membership->name); ?> - <?php echo RSMembershipHelper::getPriceFormat($this->membership->price); ?></td>
				</tr>
				<?php if (isset($this->data->coupon) && strlen($this->data->coupon)) { ?>
				<tr>
					<td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_COUPON'); ?>:</td>
					<td><?php echo $this->escape($this->data->coupon); ?></td>
				</tr>
				<?php } ?>
				<?php if ($this->extras) { ?>
					<?php foreach ($this->extras as $extra) { ?>
						<tr>
							<td width="30%" height="40"><?php echo $this->escape($extra->getParentName()); ?>:</td>
							<td><?php echo $this->escape($extra->name); ?> - <?php echo RSMembershipHelper::getPriceFormat($extra->price); ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
				<?php if ($this->membership->use_renewal_price) { ?>
				<tr>
					<td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_RENEWAL_PRICE'); ?>:</td>
					<td><?php echo RSMembershipHelper::getPriceFormat($this->total - $this->membership->price + $this->membership->renewal_price); ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_TOTAL_COST'); ?>:</td>
					<td><?php echo RSMembershipHelper::getPriceFormat($this->total); ?></td>
				</tr>
				</table>
			</div>

			<div class="item-page">
				<h3 class="page-header uk-margin-medium-top"><?php echo JText::_('COM_RSMEMBERSHIP_ACCOUNT_INFORMATION'); ?></h3>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
					<?php if ($this->choose_username) { ?>
					<tr>
						<td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_USERNAME'); ?>:</td>
						<?php if (!$this->logged) { ?>
						<td><?php echo $this->escape($this->data->username); ?></td>
						<?php } else { ?>
						<td><?php echo $this->escape($this->user->get('username')); ?></td>
						<?php } ?>
					</tr>
					<?php } ?>
					<?php if ($this->choose_password) { ?>
					<tr>
						<td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_PASSWORD'); ?>:</td>
						<td>**********</td>
					</tr>
					<?php } ?>
					<tr>
						<td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_NAME'); ?>:</td>
						<?php if (!$this->logged) { ?>
						<td><?php echo $this->escape($this->data->name); ?></td>
						<?php } else { ?>
						<td><?php echo $this->escape($this->user->get('name')); ?></td>
						<?php } ?>
					</tr>
					<tr>
						<td height="40"><?php echo JText::_( 'COM_RSMEMBERSHIP_EMAIL' ); ?>:</td>
						<?php if (!$this->logged) { ?>
						<td><?php echo $this->escape($this->data->email); ?></td>
						<?php } else { ?>
						<td><?php echo $this->escape($this->user->get('email')); ?></td>
						<?php } ?>
					</tr>
					<?php foreach ($this->fields as $field) { ?>
					<tr>
						<td height="40"><?php echo $field[0]; ?></td>
						<td><?php echo $field[1]; ?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			
			<?php if (count($this->membership_fields)>0) {?>
			<div class="item-page">
				<h3 class="page-header uk-margin-medium-top"><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_INFORMATION'); ?></h3>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
					<?php foreach ($this->membership_fields as $field) { ?>
					<tr>
						<td width="30%" height="40"><?php echo $field[0]; ?></td>
						<td><?php echo (trim($field[1])=='' ? '-' : $field[1]); ?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php } ?>

			<?php if ($this->showPayments) { ?>
			<div class="item-page">
				<h3 class="page-header uk-margin-medium-top"><?php echo JText::_('COM_RSMEMBERSHIP_PAYMENT_INFORMATION'); ?></h3>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
					<tr>
						<td width="30%" height="40"><?php echo JText::_('COM_RSMEMBERSHIP_PAY_WITH'); ?>:</td>
						<td>
						<?php
							$i = 0;
							if ( !empty($this->payments) )
							{
								foreach ($this->payments as $plugin => $paymentdetails) {
									$i++;
									$tax_value = '';
									$paymentname = '';
									if (is_array($paymentdetails))
									{
										if ($paymentdetails['tax_details'])
										{
											if ($paymentdetails['tax_details']['tax_type'] == 0)
											{
												$tax_value = $this->total * ($paymentdetails['tax_details']['tax_value'] / 100);
												$tax_value = JText::sprintf('COM_RSMEMBERSHIP_PAY_TAX_VALUE_PERCENT', $tax_value, RSMembershipHelper::getPriceFormat($tax_value), $paymentdetails['tax_details']['tax_value']);
											}
											else
											{
												$tax_value = JText::sprintf('COM_RSMEMBERSHIP_PAY_TAX_VALUE_FIXED', $paymentdetails['tax_details']['tax_value'], RSMembershipHelper::getPriceFormat($paymentdetails['tax_details']['tax_value']));
											}
										}
										$paymentname = $paymentdetails['name'];
									}
									else
									{
										$paymentname = $paymentdetails;
									}
								?>
								<p><input <?php echo $i == 1 ? 'checked="checked"' : ''; ?> type="radio" name="payment" value="<?php echo $this->escape($plugin); ?>" id="payment<?php echo $i; ?>" class="pull-left" /> <label for="payment<?php echo $i; ?>"><?php echo $this->escape($paymentname).$tax_value; ?></label></p>
							<?php } ?>
						<?php } ?>
						</td>
					</tr>
				</table>
			</div>
			<?php } ?>
			<div class="item-page">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
					<tr>
						<td width="30%" height="40"><strong><?php echo JText::_('COM_RSMEMBERSHIP_GRAND_TOTAL'); ?></strong>:</td>
						<td><span id="rsm_grand_total" data-fixedvalue="<?php echo $this->total;?>"><?php echo RSMembershipHelper::getPriceFormat($this->total); ?></span></td>
					</tr>
				</table>
			</div>
			<div class="form-actions uk-margin-medium-top">
				<button type="button" class="button btn pull-left" onclick="document.location='<?php echo JRoute::_('index.php?option=com_rsmembership&view=subscribe&cid='.$this->membership->id.'&task=back'); ?>'" name="Cancel"><?php echo JText::_('COM_RSMEMBERSHIP_BACK'); ?></button>
				<button type="submit" class="button btn btn-success pull-right"><?php echo JText::_('COM_RSMEMBERSHIP_SUBSCRIBE'); ?></button>
			</div>
			<?php echo JHtml::_('form.token');?>
			<input type="hidden" name="option" value="com_rsmembership" />
			<input type="hidden" name="view" value="subscribe" />
			<input type="hidden" name="task" value="paymentredirect" />
			<input type="hidden" name="cid" value="<?php echo $this->membership->id; ?>" />
		</form>
	</div>
</div>