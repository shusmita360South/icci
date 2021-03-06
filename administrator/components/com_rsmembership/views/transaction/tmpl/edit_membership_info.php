<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

if ($this->item->membership_info) {
?>
	<table class="rsmem_transaction_info_table">
		<?php foreach ($this->item->membership_info as $field) { ?>
		<tr>
			<td width="200"><?php echo $field[0]; ?></td>
			<td><?php echo $field[1]; ?></td>
		</tr>
		<?php } ?>
	</table>
<?php } else {
	echo JText::_('COM_RSMEMBERSHIP_NO_INFO');
}