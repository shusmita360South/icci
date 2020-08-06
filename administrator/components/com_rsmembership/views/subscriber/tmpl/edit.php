<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.tabstate');

JText::script('COM_RSMEMBERSHIP_ANONYMISE_BUTTON_WARNING');
?>

<script type="text/javascript">
Joomla.submitbutton = function(pressbutton)
{
    if (pressbutton === 'subscriber.anonymise')
    {
        if (!confirm(Joomla.JText._('COM_RSMEMBERSHIP_ANONYMISE_BUTTON_WARNING')))
        {
            return false;
        }
    }

    Joomla.submitform(pressbutton);
}
</script>

<?php
// add the tab title
$this->tabs->addTitle(JText::_($this->fieldsets['main']->label), 'user-info');
// load content
$user_info_content = $this->loadTemplate('user_info');
// add the tab content
$this->tabs->addContent($user_info_content);

if ( !$this->temp ) 
{
	$this->tabs->addTitle(JText::_('COM_RSMEMBERSHIP_MEMBERSHIPS'), 'memberships');
	$this->tabs->addContent($this->loadTemplate('memberships'));

	$this->tabs->addTitle(JText::_('COM_RSMEMBERSHIP_TRANSACTION_HISTORY'), 'transactions');
	$this->tabs->addContent($this->loadTemplate('transactions'));

	$this->tabs->addTitle(JText::_('COM_RSMEMBERSHIP_ACCESS_LOGS'), 'logs');
	$this->tabs->addContent($this->loadTemplate('logs'));

    $this->tabs->addTitle(JText::_('COM_RSMEMBERSHIP_ANONYMISE'), 'anonymise');
    $this->tabs->addContent($this->loadTemplate('anonymise'));
}
// render tabs
$this->tabs->render();