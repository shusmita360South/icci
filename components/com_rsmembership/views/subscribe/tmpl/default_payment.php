<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2020 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->showPayments) { ?>
<div class="item-page">
<h3 class="page-header"><?php echo JText::_('COM_RSMEMBERSHIP_PAYMENT_INFORMATION'); ?></h3>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table" id="rsm_subscribe_default_payement_form">
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
							$tax_value = JText::sprintf('COM_RSMEMBERSHIP_PAY_TAX_VALUE_PERCENT', $tax_value,RSMembershipHelper::getPriceFormat($tax_value), $paymentdetails['tax_details']['tax_value']);
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
<div class="item-page">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="rsmembership_form_table">
		<tr>
			<td width="30%" height="40"><strong><?php echo JText::_('COM_RSMEMBERSHIP_GRAND_TOTAL'); ?></strong>:</td>
			<td><span id="rsm_grand_total" data-fixedvalue="<?php echo $this->grand_total;?>"><?php echo RSMembershipHelper::getPriceFormat($this->total); ?></span></td>
		</tr>
	</table>
</div>
<?php } ?>