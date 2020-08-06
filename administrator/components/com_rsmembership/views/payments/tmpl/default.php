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
$saveOrder	= $listOrder == 'ordering';

JHtml::_('behavior.framework');

if ($saveOrder)
	JHtml::_('sortablelist.sortable', 'sortTable', 'adminForm', strtolower($listDirn), 'index.php?option=com_rsmembership&task=payments.saveOrderAjax&tmpl=component');

JFactory::getApplication()->enqueueMessage(JText::_('COM_RSMEMBERSHIP_PAYMENT_TRANSLATE'), 'notice');
?>
	<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=payments'); ?>" method="post" name="adminForm" id="adminForm">
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
			<?php echo $this->filterbar->show(); ?>
			<table class="adminlist table table-striped" id="sortTable">
				<thead>
					<tr>
						<th width="5"><?php echo JText::_( '#' ); ?></th>
						<?php echo $this->ordering->showHead($listDirn, $listOrder, 'ordering', array('items' => $this->payments, 'saveTask' => 'payments.saveorder')); ?>
						<th><?php echo JText::_('COM_RSMEMBERSHIP_PAYMENT_TYPE'); ?></th>
						<th><?php echo JText::_('COM_RSMEMBERSHIP_PAYMENT_LIMITATIONS'); ?></th>
						<th width="1"><?php echo JText::_('COM_RSMEMBERSHIP_CONFIGURE'); ?></th>
						<th width="1"><?php echo JText::_('JPUBLISHED'); ?></th>
					</tr>
				</thead>
			<?php
			$k = 0;
			$i = 0;
			$j = 0;
			$n = count($this->payments);
			foreach ($this->payments as $row) 
			{
				$is_wire = isset($row->id);
				if ($is_wire) {
				$link = JRoute::_('index.php?option=com_rsmembership&task=payment.edit&id='.$row->id);
				?>
				<tr class="row<?php echo $k; ?>">
					<td align="center"><?php echo JHtml::_('grid.id', $j, $row->id); ?></td>
					<?php $this->ordering->showRow($saveOrder, $row->ordering, array('context' => 'payments', 'pagination' => $this->pagination, 'listDirn' => $listDirn, 'i' => $i)); ?>
					<td><a href="<?php echo $link; ?>"><?php echo $this->getTranslation($this->escape($row->name)); ?></a></td>
					<td>&nbsp;</td>
					<td align="center"><a href="<?php echo $link; ?>"><?php echo JHtml::image('com_rsmembership/admin/config.png', JText::_('COM_RSMEMBERSHIP_CONFIGURE'), null, true); ?></a></td>
					<td width="1%" nowrap="nowrap" align="center"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'payments.');?></td>
				</tr>
				<?php $j++; 
				} else {
				$link = JRoute::_('index.php?option=com_plugins&task=plugin.edit&extension_id='.$row->cid);
				?>
				<tr class="row<?php echo $k; ?>">
					<td>&nbsp;</td>
					<td align="center"></td>
					<td><a href="<?php echo $link; ?>"><?php echo $this->escape($row->name); ?></a></td>
					<td><?php echo $row->limitations; ?></td>
					<td align="center"><a href="<?php echo $link; ?>"><?php echo JHtml::image('com_rsmembership/admin/config.png', JText::_('COM_RSMEMBERSHIP_CONFIGURE'), null, true); ?></a></td>
					<td>&nbsp;</td>
				</tr>
			<?php
				}
				$i++;
				$k=1-$k;
			}
			?>
			</table>
			<?php echo JHtml::_( 'form.token' ); ?>
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="task" value="" />
	</div>
</form>