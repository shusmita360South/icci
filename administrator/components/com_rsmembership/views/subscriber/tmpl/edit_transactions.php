<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<table class="adminlist table table-striped">
	<thead>
	<tr>
		<th width="5"><?php echo JText::_( '#' ); ?></th>
		<th width="20"><?php echo JText::_('Delete'); ?></th>
		<th width="140"><?php echo JText::_('COM_RSMEMBERSHIP_TYPE'); ?></th>
		<th><?php echo JText::_('COM_RSMEMBERSHIP_DETAILS'); ?></th>
		<th width="200"><?php echo JText::_('COM_RSMEMBERSHIP_DATE'); ?></th>
		<th width="110"><?php echo JText::_('COM_RSMEMBERSHIP_IP'); ?></th>
		<th><?php echo JText::_('COM_RSMEMBERSHIP_PRICE'); ?></th>
		<th><?php echo JText::_('COM_RSMEMBERSHIP_STATUS'); ?></th>
		<th><?php echo JText::_('COM_RSMEMBERSHIP_INVOICE'); ?></th>
		<th><?php echo JText::_('COM_RSMEMBERSHIP_GATEWAY'); ?></th>
		<th><?php echo JText::_('COM_RSMEMBERSHIP_HASH'); ?></th>
	</tr>
	</thead>
	<?php
	foreach ($this->transactions as $i => $row) { ?>
	<tr>
		<td><?php echo $i+1; ?></td>
		<td align="center"><a class="delete-item" onclick="return confirm('<?php echo JText::_('COM_RSMEMBERSHIP_CONFIRM_DELETE'); ?>')" href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=transactions.remove&cid[]='.$row->id.'&'.JSession::getFormToken().'=1&tabposition=2&user_id='.$this->item->user_id); ?>"><?php echo JHtml::image('com_rsmembership/admin/remove.png', JText::_('COM_RSMEMBERSHIP_DELETE'), null, true); ?></a></td>
		<td><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_'.strtoupper($row->type)); ?></td>
		<td><?php
			$params = RSMembershipHelper::parseParams($row->params);
			switch ($row->type)
			{
				case 'new':
					if (!empty($params['membership_id']))
						echo $this->cache->memberships[$params['membership_id']];
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
		<td><?php echo RSMembershipHelper::showDate($row->date); ?></td>
		<td><?php echo $this->escape($row->ip); ?></td>		
		<td><?php echo RSMembershipHelper::getPriceFormat($row->price); ?></td>
		<td><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_STATUS_'.strtoupper($row->status)); ?></td>
		<td>
			<?php if ($row->status == 'completed' && $row->membership_data && $row->membership_data->use_membership_invoice) { ?>
				<a class="btn" href="<?php echo JRoute::_("index.php?option=com_rsmembership&task=transaction.outputinvoice&user_id=$row->user_id&id=$row->id"); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_INVOICE'); ?></a>
			<?php } ?>
		</td>
		<td><?php echo $this->escape($row->gateway); ?></td>
		<td><?php echo empty($row->hash) ? '<em>'.JText::_('COM_RSMEMBERSHIP_NO_HASH').'</em>' : $this->escape($row->hash); ?></td>
	</tr>
	<?php
	}
	?>
</table>