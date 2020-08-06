<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>

<h2><?php echo JText::_('COM_RSMEMBERSHIP_VIEWING_TRANSACTION_LOG'); ?></h2>
<div>
	<?php echo $this->log ? nl2br($this->escape($this->log)) : JText::_('COM_RSMEMBERSHIP_LOG_IS_EMPTY'); ?>
</div>