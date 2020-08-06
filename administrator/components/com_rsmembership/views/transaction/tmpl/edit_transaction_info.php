<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

$has_tax = (float)$this->item->transaction_data->tax_value > 0 ? true : false;
?>
<table class="rsmem_transaction_info_table">
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_DATE');?></td>
		<td><?php echo RSMembershipHelper::showDate($this->item->transaction_data->date) ?></td>
	</tr>
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_TYPE');?></td>
		<td><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_'.strtoupper($this->item->transaction_data->type)); ?></td>
	</tr>
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_DETAILS');?></td>
		<td>
		<?php
				$params = RSMembershipHelper::parseParams($this->item->transaction_data->params);
				switch ($this->item->transaction_data->type)
				{
					case 'new':
						if (!empty($params['membership_id']))
							echo isset($this->cache->memberships[$params['membership_id']]) ? $this->cache->memberships[$params['membership_id']] : JText::_('COM_RSMEMBERSHIP_COULD_NOT_FIND_MEMBERSHIP');
						if (!empty($params['extras']))
							foreach ($params['extras'] as $extra)
								if (!empty($extra))
									echo '<br />- '.$this->cache->extra_values[$extra];
					break;
					
					case 'upgrade':
						if (!empty($params['from_id']) && !empty($params['to_id']))
							echo $this->cache->memberships[$params['from_id']].' -&gt; '.$this->cache->memberships[$params['to_id']];
					break;
					
					case 'addextra':
						if (!empty($params['extras']))
							foreach ($params['extras'] as $extra)
								echo $this->cache->extra_values[$extra].'<br />';
					break;
					
					case 'renew':
						if (!empty($params['membership_id']))
							echo $this->cache->memberships[$params['membership_id']];
					break;
				}
				?>
		</td>
	</tr>
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_PRICE');?></td>
		<td><?php echo $this->escape(RSMembershipHelper::getPriceFormat($this->item->transaction_data->price, $this->item->transaction_data->currency)).($has_tax ? JText::_('COM_RSMEMBERSHIP_TRANSACTION_PRICE_TAX_INCLUDED') : ''); ?></td>
	</tr>
	<?php if ($has_tax) { ?>
		<tr>
			<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_TAX_TYPE');?></td>
			<td><?php echo ($this->item->transaction_data->tax_value == '1' ? JText::_('COM_RSMEMBERSHIP_TRANSACTION_TAX_TYPE_FIXED') : JText::_('COM_RSMEMBERSHIP_TRANSACTION_TAX_TYPE_PERCENT')); ?></td>
		</tr>
		<tr>
			<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_TAX_VALUE');?></td>
			<td><?php echo $this->escape(RSMembershipHelper::getPriceFormat($this->item->transaction_data->tax_value, $this->item->transaction_data->currency)); ?></td>
		</tr>
	<?php } ?>
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_COUPON');?></td>
		<td><?php echo strlen($this->item->transaction_data->coupon) == 0 ? '<em>'.JText::_('COM_RSMEMBERSHIP_NO_COUPON').'</em>' : $this->escape($this->item->transaction_data->coupon); ?></td>
	</tr>
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_STATUS');?></td>
		<td><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_STATUS_'.strtoupper($this->item->transaction_data->status)); ?></td>
	</tr>
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_GATEWAY');?></td>
		<td><?php echo $this->escape($this->item->transaction_data->gateway); ?></td>
	</tr>
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_IP');?></td>
		<td><?php echo $this->escape($this->item->transaction_data->ip); ?></td>
	</tr>
	<tr>
		<td width="200"><?php echo JText::_('COM_RSMEMBERSHIP_HASH');?></td>
		<td><?php echo !strlen($this->item->transaction_data->hash) ? '<em>'.JText::_('COM_RSMEMBERSHIP_NO_HASH').'</em>' : $this->escape($this->item->transaction_data->hash); ?></td>
	</tr>
</table>
