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

// add the tab title
$this->tabs->addTitle(JText::_($this->fieldsets['main']->label), 'transaction-info');
// load content
$transaction_info_content = $this->loadTemplate('transaction_info');
// add the tab content
$this->tabs->addContent($transaction_info_content);


$this->tabs->addTitle(JText::_('COM_RSMEMBERSHIP_SUBSCRIBER_INFO'), 'user-info');
// load content
$user_info_content = $this->loadTemplate('user_info');
// add the tab content
$this->tabs->addContent($user_info_content);

$this->tabs->addTitle(JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_INFO'), 'membership-info');
// load content
$membership_info_content = $this->loadTemplate('membership_info');
// add the tab content
$this->tabs->addContent($membership_info_content);

$this->tabs->addTitle(JText::_('COM_RSMEMBERSHIP_PAYMENT_LOG'), 'payment-log');
// load content
$payment_log_content = $this->loadTemplate('payment_log');
// add the tab content
$this->tabs->addContent($payment_log_content);

$this->tabs->render();