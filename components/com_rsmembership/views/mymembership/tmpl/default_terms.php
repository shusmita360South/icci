<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>
<p><?php echo JText::_('COM_RSMEMBERSHIP_PLEASE_AGREE'); ?></p>
<p><?php echo JText::_('COM_RSMEMBERSHIP_PLEASE_SCROLL'); ?></p>
<div id="rsm_mymembership_container">
<?php echo $this->terms; ?>
</div> <!-- rsm_mymembership_container -->
<form method="post" action="<?php echo $this->action; ?>" id="rsm_mymembership_form">
<input type="hidden" name="agree" value="1" />
<button type="submit"><?php echo JText::_('COM_RSMEMBERSHIP_I_AGREE'); ?></button>
</form>