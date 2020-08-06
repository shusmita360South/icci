<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

?>

<?php if (!isset($this->tmpl) || $this->tmpl != 'component') { ?>
<style>
	#memberships .modal-body {
		max-height: initial;
	}
</style>

<div id="addmemberships_ajax">
	<a class="btn btn-info btn-small" data-toggle="modal" role="button" title="<?php echo JText::_('COM_RSMEMBERSHIP_NEW_MEMBERSHIP');?>" href="#rsm_membership_add"><?php echo JText::_('COM_RSMEMBERSHIP_NEW_MEMBERSHIP'); ?></a>
	<span class="rsmembership_clear"></span>
<?php } ?>
	<table class="adminlist table table-striped" id="addmemberships">
		<thead>
		<tr>
			<th width="5"><?php echo JText::_( '#' ); ?></th>
			<th width="20"><?php echo JText::_('Delete'); ?></th>
			<th><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP'); ?></th>
			<th><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_PRICE'); ?></th>
			<th><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_START'); ?></th>
			<th><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_END'); ?></th>
			<th colspan="2"><?php echo JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_STATUS'); ?></th>
			<th width="80"><?php echo JText::_('JPUBLISHED'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($this->item->memberships as $i => $row)
		{
			if ($row->status == 0) // active
				$image = 'legacy/publish_g.png';
			elseif ($row->status == 1) // pending
				$image = 'legacy/publish_y.png';
			elseif ($row->status == 2) // expired
				$image = 'legacy/publish_r.png';
			elseif ($row->status == 3) // cancelled
				$image = 'legacy/publish_x.png';
			?>
			<tr>
				<td><?php echo $i+1; ?></td>
				<td align="center">
					<a class="delete-item" onclick="return confirm('<?php echo JText::_('COM_RSMEMBERSHIP_CONFIRM_DELETE'); ?>')" href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=membership_subscriber.remove&cids[]='.$row->id.'&'.JSession::getFormToken().'=1&user_id='.$row->user_id.'&tabposition=1'); ?>"><?php echo JHtml::image('com_rsmembership/admin/remove.png', JText::_('COM_RSMEMBERSHIP_DELETE'), null, true); ?></a>
				</td>
				<td><a data-toggle="modal" role="button" href="#rsm_membership_edit_<?php echo $row->id; ?>" ><?php echo $row->name != '' ? $this->escape($row->name) : JText::_('COM_RSMEMBERSHIP_NO_TITLE'); ?></a></td>
				<td>
					<?php echo RSMembershipHelper::getPriceFormat($row->price, $row->currency); ?>
				</td>
				<td><?php echo RSMembershipHelper::showDate( JFactory::getDate($row->membership_start)->toUnix() ); ?></td>
				<td><?php echo $row->membership_end != '0000-00-00 00:00:00' ? RSMembershipHelper::showDate( JFactory::getDate($row->membership_end)->toUnix() ) : JText::_('COM_RSMEMBERSHIP_UNLIMITED'); ?></td>
				<td><?php echo JText::_('COM_RSMEMBERSHIP_STATUS_'.$row->status); ?></td>
				<td align="center"><?php echo JHtml::image('com_rsmembership/admin/'.$image, JText::_('COM_RSMEMBERSHIP_STATUS'), null, true); ?></td>
				<td align="center">
					<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=membership_subscriber.'.( $row->published ? 'unpublish' : 'publish').'&cids[]='.$row->id.'&'.JSession::getFormToken().'=1&user_id='.$row->user_id.'&tabposition=1'); ?>"><?php echo JHtml::image('com_rsmembership/admin/'.($row->published ? 'tick' : 'disabled').'.png', JText::_(($row->published ? 'JUNPUBLISH' : 'JPUBLISH')), null, true); ?></a>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
<?php if (!isset($this->tmpl) || $this->tmpl != 'component') { ?>
</div>
<?php } ?>

<?php
if (!isset($this->tmpl) || $this->tmpl != 'component') {
	echo JHtml::_('bootstrap.renderModal', 'rsm_membership_add', array(
		'title' => JText::_('COM_RSMEMBERSHIP_NEW_MEMBERSHIP'),
		'url' => JRoute::_('index.php?option=com_rsmembership&task=membership_subscriber.add&user_id=' . $this->item->user_id . '&tmpl=component'),
		'height' => '475',
		'width' => '660',
		'footer' => '<button type="button" class="btn btn-success" onclick="rsm_trigger_iframe_action(\'membership_save_button\', \'rsm_membership_add\')">'.JText::_('COM_RSMEMBERSHIP_SAVE').'</button>'
	));

	foreach ($this->item->memberships as $i => $row) {
		echo JHtml::_('bootstrap.renderModal', 'rsm_membership_edit_'.$row->id, array(
			'title' => '&nbsp;',
			'url' => JRoute::_('index.php?option=com_rsmembership&task=membership_subscriber.edit&tmpl=component&id='.$row->id),
			'height' => '475',
			'width' => '660',
			'footer' => '<button type="button" class="btn btn-success" onclick="rsm_trigger_iframe_action(\'membership_save_button\', \'rsm_membership_edit_'.$row->id.'\')">'.JText::_('COM_RSMEMBERSHIP_SAVE').'</button>'
		));
	}
	?>
	<script type="text/javascript">
		function rsm_trigger_iframe_action(buttonId, modal_id) {
			// in case the iframe is not loaded do nothing when the button is pressed
			if (jQuery('#'+modal_id).find('.iframe').contents().find('#'+buttonId).length) {
				jQuery('#' + modal_id).find('.iframe').contents().find('#' + buttonId).trigger('click');
			} else {
				return;
			}
		}
	</script>
	<?php
}
?>
