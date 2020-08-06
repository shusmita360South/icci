<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>

<div class="item-page">
	<?php if ($this->params->get('show_page_heading', 1)) { ?>
	<div class="page-header">
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
	<?php } ?>

	<div id="rsm_terms_container">	
		<?php
		if (RSMembershipHelper::getConfig('trigger_content_plugins')) {
			$this->terms->description = JHtml::_('content.prepare', $this->terms->description);
		}
		echo $this->terms->description;
		?>
	</div> <!-- rsm_terms_container -->
</div>