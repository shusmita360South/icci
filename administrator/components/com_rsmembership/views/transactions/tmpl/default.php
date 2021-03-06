<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=transactions'); ?>" method="post" name="adminForm" id="adminForm">

	<div id="j-sidebar-container" class="span2"><?php echo $this->sidebar; ?></div>
	<div id="j-main-container" class="span10">
	<?php echo $this->filterbar->show(); ?>
		<table class="table adminlist table-hovered">
			<thead>
				<tr>
					<th width="5">#</th>
					<th width="5"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID','t.id', $listDirn, $listOrder); ?></th>
					<th width="20"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);"/></th>
					<th width="140"><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION'); ?></th>
					<th width="140"><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_DATE','t.date', $listDirn, $listOrder); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_EMAIL','email', $listDirn, $listOrder); ?></th>
					<th width="140">
					<?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_TYPE','t.type', $listDirn, $listOrder); ?>
					</th>
					<th><?php echo JText::_('COM_RSMEMBERSHIP_DETAILS'); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_PRICE','t.price', $listDirn, $listOrder); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_MEMBERSHIP_COUPON','t.coupon', $listDirn, $listOrder); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_STATUS','t.status', $listDirn, $listOrder); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_GATEWAY','t.gateway', $listDirn, $listOrder); ?></th>
					<th width="110"><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_IP','t.ip', $listDirn, $listOrder); ?></th>
					<th><?php echo JText::_('COM_RSMEMBERSHIP_HASH'); ?></th>
				</tr>
			</thead>
		<?php
		$k = 0;
		foreach ($this->items as $i => $row) 
		{
		$css_status = ( $row->status == 'completed' ? 'success' : ( $row->status == 'pending' ? 'warning' : 'error' ) );
		?>
			<tr class="row<?php echo $k; ?> <?php echo $css_status; ?>">
				<td width="1%" nowrap="nowrap"><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo $row->id; ?></td>
				<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
				<td>
					<a class="btn btn-primary" href="index.php?option=com_rsmembership&task=transaction.edit&id=<?php echo $row->id;?>"><?php echo JText::_('COM_RSMEMBERSHIP_VIEW'); ?></a>
					<?php if ($row->status == 'completed' && $row->membership_data && $row->membership_data->use_membership_invoice) { ?>
						<a class="btn pull-right" href="<?php echo JRoute::_("index.php?option=com_rsmembership&task=transaction.outputinvoice&user_id=$row->user_id&id=$row->id"); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_INVOICE'); ?></a>
					<?php } ?>
				</td>
				<td width="1%" nowrap="nowrap"><?php echo RSMembershipHelper::showDate($row->date); ?></td>
				<td><?php echo !empty($row->email) ? '<a href="index.php?option=com_rsmembership&task=subscriber.edit&id='.$row->user_id.(!$row->user_id ? '&temp_id='.$row->id : '').'">'.$this->escape($row->email).'</a>' : '<em>'.JText::_('COM_RSMEMBERSHIP_NO_EMAIL').'</em>'; ?></td>
				<td width="1%" nowrap="nowrap"><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_'.strtoupper($row->type)); ?></td>
				<td><?php
				$params = RSMembershipHelper::parseParams($row->params);
				switch ($row->type)
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
				<td class="text-right"><?php echo $this->escape(RSMembershipHelper::getPriceFormat($row->price, $row->currency)); ?></td>
				<td><?php echo strlen($row->coupon) == 0 ? '<em>'.JText::_('COM_RSMEMBERSHIP_NO_COUPON').'</em>' : $this->escape($row->coupon); ?></td>
				<td><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_STATUS_'.strtoupper($row->status)); ?>
					<a class="btn pull-right" data-toggle="modal" role="button" href="#rsm_membership_transaction_log_<?php echo $row->id; ?>"><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_VIEW_LOG'); ?></a>
				</td>
				<td><?php echo $this->escape($row->gateway); ?></td>
				<td width="1%" nowrap="nowrap"><?php echo $this->escape($row->ip); ?></td>
				<td><?php echo !strlen($row->hash) ? '<em>'.JText::_('COM_RSMEMBERSHIP_NO_HASH').'</em>' : $this->escape($row->hash); ?></td>
			</tr>
		<?php
			$k=1-$k;
		}
		?>
			<tr><td colspan="14" align="center" class="center"><?php echo $this->pagination->getListFooter(); ?></td></tr>
		</table>
	</div>

		<?php echo JHtml::_( 'form.token' ); ?>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
</form>

<?php

foreach ( $this->items as $i => $row ) {
	echo JHtml::_('bootstrap.renderModal', 'rsm_membership_transaction_log_'.$row->id, array(
		'title' => '&nbsp;',
		'url' => JRoute::_('index.php?option=com_rsmembership&view=transactions&layout=log&cid='.$row->id.'&tmpl=component'),
		'height' => '475',
		'width' => '460',
	));
}