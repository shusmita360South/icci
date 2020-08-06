<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSOrdering
{
	public function showHead($listDirn, $listOrder, $orderField='ordering', $unused=array()) { ?>
		<th width="1%" class="nowrap center">
			<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', $orderField, $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
		</th>
		<?php
	}

	public function showRow($saveOrder, $itemOrdering, $unused=array()) {
		$disableClassName = '';
		$disabledLabel	  = '';
		if (!$saveOrder) {
			$disabledLabel    = JText::_('JORDERINGDISABLED');
			$disableClassName = 'inactive tip-top';
		}
		?>
		<td class="order nowrap center hidden-phone">
			<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
				<i class="icon-menu"></i>
			</span>
			<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $itemOrdering; ?>" class="width-20 text-area-order " />
		</td>
		<?php
	}
}