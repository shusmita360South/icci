<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>

<table class="adminlist table table-striped" id="addsubscriberfiles">
	<thead>
	<tr>
		<th width="5"><?php echo JText::_( '#' ); ?></th>
		<th width="20">&nbsp;</th>
		<th width="20"><?php echo JText::_('COM_RSMEMBERSHIP_DELETE'); ?></th>
		<th><?php echo JText::_('COM_RSMEMBERSHIP_PATH'); ?></th>
		<th width="80"><?php echo JText::_('COM_RSMEMBERSHIP_PUBLISHED'); ?></th>
	</tr>
	</thead>
	<?php
	$k = 0;

	foreach ($this->item->attachments as $i => $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->item->attachmentsPagination->getRowOffset($i); ?></td>
			<td><?php echo JHtml::_('grid.id', $this->email_type.$i, $row->id, false, 'cid_attachments');?></td>
			<td align="center">
			<a class="delete-item" onclick="return confirm('<?php echo JText::_('COM_RSMEMBERSHIP_CONFIRM_DELETE'); ?>')" href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=membership.attachmentsremove&cid_attachments[]='.$row->id.'&'.JSession::getFormToken().'=1&tabposition=6'); ?>"><?php echo JHtml::image('com_rsmembership/admin/remove.png', JText::_('COM_RSMEMBERSHIP_DELETE'), null, true); ?></a>
			</td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=file.edit&cid='.$row->path); ?>" target="_blank"><?php echo $row->path; ?></a></td>
			<td align="center" class="center"><?php echo JHtml::_('jgrid.published', $row->published, $this->email_type.$i, 'membership.attachments');?></td> 
		</tr>
	<?php
		$k=1-$k;
	}
	?>
</table>