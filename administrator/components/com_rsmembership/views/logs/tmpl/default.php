<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

$listOrder 	= $this->escape($this->state->get('list.ordering'));
$listDirn 	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=logs&user_id='.$this->userId); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php $this->filterbar->show(); ?>
	
	<table class="adminlist table table-striped" id="articleList">
		<thead>
		<tr>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( '#' ); ?></th>
			<th width="1%" nowrap="nowrap"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
			<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_DATE', 'date', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_IP', 'ip', $listDirn, $listOrder); ?></th>
			<th><?php echo JHtml::_('grid.sort', 'COM_RSMEMBERSHIP_PATH', 'path', $listDirn, $listOrder); ?></th>
		</tr>
		</thead>
	<?php foreach ($this->items as $i => $item) { ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td width="1%" nowrap="nowrap"><?php echo $this->pagination->getRowOffset($i); ?></td>
			<td width="1%" nowrap="nowrap"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
			<td width="1%" nowrap="nowrap"><?php echo RSMembershipHelper::showDate($item->date); ?></td>
			<td width="1%" nowrap="nowrap"><?php echo $this->escape($item->ip); ?></td>
			<td><?php echo $this->escape($item->path); ?></td>
		</tr>
	<?php } ?>
	<tfoot>
		<tr>
			<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	</table>
	
	<div>
		<?php echo JHtml::_( 'form.token' ); ?>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="user_id" value="<?php echo $this->userId; ?>" />
	</div>
	</div>
</form>