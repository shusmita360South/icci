<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>
	<?php if ($this->params->get('show_page_heading', 1)) { ?>
	<div class="page-header">
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
	<?php } ?>

	<form action="<?php echo JRoute::_( RSMembershipRoute::Categories() ); ?>" method="post" name="adminForm" id="rsm_categories_form">
	<?php $k = 1; ?>
	<?php $i = 0; ?>
	<?php foreach ($this->items as $item) { ?>
	<div class="item-page">
		<div class="page-header">
			<h2 class="sectiontableentry<?php echo $k . $this->escape($this->params->get('pageclass_sfx')); ?>" >
				<a href="<?php echo JRoute::_(RSMembershipRoute::Memberships($item->id, $this->Itemid)); ?>"><?php echo $this->escape($item->name); ?></a>
				<?php if ($this->params->get('show_memberships', 0)) { ?> 
					<span class="badge badge-info"><?php echo $item->memberships; ?></span>
				<?php } ?>
			</h2>
		</div>
		<?php if ($this->params->get('show_category_description', 0)) { ?>
				<div class="rsm_description">
					<?php
					if (RSMembershipHelper::getConfig('trigger_content_plugins')) {
						$item->description = JHtml::_('content.prepare', $item->description);
					}
					echo $item->description;
					?>
				</div>
		<?php } ?>

		<?php $k = $k == 1 ? 2 : 1; ?>
		<?php $i++; ?>
	</div>
	<?php } ?>

	<?php if ($this->params->get('show_pagination', 1)) { ?>
	<div class="sectiontablefooter<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" align="center">
		<div class="pagination">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<?php echo $this->pagination->getPagesCounter(); ?>
	</div>
	<?php } ?>

	<input type="hidden" name="filter_order" value="" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>