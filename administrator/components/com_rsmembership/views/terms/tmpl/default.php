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
	JHtml::_('sortablelist.sortable', 'sortTable', 'adminForm', strtolower($listDirn), 'index.php?option=com_rsmembership&task=terms.saveOrderAjax&tmpl=component');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=terms'); ?>" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php echo $this->filterbar->show(); ?>
		<table class="adminlist table table-striped" id="sortTable">
			<thead>
				<tr>
					<th width="5"><?php echo JHtml::_('grid.sort', '#', 'id', $listDirn, $listOrder); ?></th>
					<?php echo $this->ordering->showHead($listDirn, $listOrder, 'ordering', array('items' => $this->items, 'saveTask' => 'terms.saveorder')); ?>
					<th width="20"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_TERM', 'name', $listDirn, $listOrder); ?></th>
					<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_PUBLISHED', 'published', $listDirn, $listOrder); ?></th>
				</tr>
			</thead>
			<?php
			$k = 0;
			foreach ( $this->items as $i => $row )
			{
			?>
				<tr class="row<?php echo $k; ?>">
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<?php $this->ordering->showRow($saveOrder, $row->ordering, array('context' => 'terms', 'pagination' => $this->pagination, 'listDirn' => $listDirn, 'i' => $i)); ?>
					<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
					<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=term.edit&id='.$row->id); ?>"><?php echo $row->name != '' ? $this->escape($row->name) : JText::_('COM_RSMEMBERSHIP_NO_TITLE'); ?></a></td>
					<td width="1%" nowrap="nowrap" align="center"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'terms.');?></td>
				</tr>
			<?php
				$k=1-$k;
			}
			?>
				<tfoot>
					<tr>
						<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
					</tr>
				</tfoot>
			</table>
		
		<?php echo JHtml::_( 'form.token' ); ?>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
	</div>
</form>