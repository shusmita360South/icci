<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');
?>

<p><?php echo JText::_('COM_RSMEMBERSHIP_LAST_LOGS'); ?> <a class="btn btn-primary" target="_blank" href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=logs&user_id='.$this->item->user_id); ?>"><strong><?php echo JText::_('COM_RSMEMBERSHIP_VIEW_FULL_LOG'); ?></strong></a></p>
<table class="adminlist table table-striped">
	<thead>
	<tr>
		<th width="5"><?php echo JText::_( '#' ); ?></th>
		<th width="200"><?php echo JText::_('COM_RSMEMBERSHIP_DATE'); ?></th>
		<th width="110"><?php echo JText::_('COM_RSMEMBERSHIP_IP'); ?></th>
		<th><?php echo JText::_('COM_RSMEMBERSHIP_PATH'); ?></th>
	</tr>
	</thead>
	<?php
	foreach ($this->item->logs as $i => $row)
	{
	?>
		<tr>
			<td><?php echo $i+1; ?></td>
			<td><?php echo RSMembershipHelper::showDate($row->date); ?></td>
			<td><?php echo $this->escape($row->ip); ?></td>
			<td><?php echo $this->escape($row->path); ?></td>
		</tr>
	<?php
	}
	?>
</table>
