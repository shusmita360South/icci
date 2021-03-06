<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

abstract class RSMembershipToolbarHelper
{
	public static $entries = array();

	public static function addToolbar($ViewName) 
	{
		self::addEntry(JText::_('OVERVIEW'), 'index.php?option=com_rsmembership&view=rsmembership', $ViewName == 'rsmembership' || $ViewName == '');
		self::addEntry(JText::_('TRANSACTIONS'), 'index.php?option=com_rsmembership&view=transactions', $ViewName == 'transactions');
		self::addEntry(JText::_('MEMBERSHIPS'), 'index.php?option=com_rsmembership&view=memberships', $ViewName == 'memberships');
		self::addEntry(JText::_('MEMBERSHIP_FIELDS'), 'index.php?option=com_rsmembership&view=membership_fields', $ViewName == 'membership_fields');
		self::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_rsmembership&view=categories', $ViewName == 'categories');
		self::addEntry(JText::_('MEMBERSHIP_EXTRAS'), 'index.php?option=com_rsmembership&view=extras', $ViewName == 'extras');
		self::addEntry(JText::_('MEMBERSHIP_UPGRADES'), 'index.php?option=com_rsmembership&view=upgrades', $ViewName == 'upgrades');
		self::addEntry(JText::_('COUPONS'), 'index.php?option=com_rsmembership&view=coupons', $ViewName == 'coupons');
		self::addEntry(JText::_('PAYMENT_INTEGRATIONS'), 'index.php?option=com_rsmembership&view=payments', $ViewName == 'payments');
		self::addEntry(JText::_('FILES'), 'index.php?option=com_rsmembership&view=files', $ViewName == 'files');
		self::addEntry(JText::_('FILE_TERMS'), 'index.php?option=com_rsmembership&view=terms', $ViewName == 'terms');
		self::addEntry(JText::_('SUBSCRIBERS'), 'index.php?option=com_rsmembership&view=subscribers', $ViewName == 'subscribers');
		self::addEntry(JText::_('SUBSCRIPTIONS'), 'index.php?option=com_rsmembership&view=subscriptions', $ViewName == 'subscriptions');
		self::addEntry(JText::_('FIELDS'), 'index.php?option=com_rsmembership&view=fields', $ViewName == 'fields');
		self::addEntry(JText::_('REPORTS'), 'index.php?option=com_rsmembership&view=reports', $ViewName == 'reports');
		self::addEntry(JText::_('CONFIGURATION'), 'index.php?option=com_rsmembership&view=configuration', $ViewName == 'configuration');
		self::addEntry(JText::_('SYSLOGS'), 'index.php?option=com_rsmembership&view=syslogs', $ViewName == 'syslogs');
	}

	protected static function addEntry($lang_key, $url, $default=false) 
	{
		JHtmlSidebar::addEntry(JText::_('COM_RSMEMBERSHIP_'.$lang_key), JRoute::_($url), $default);
	}

	public static function addFilter($text, $key, $options, $type = null) 
	{
		if (empty($type)) {
			JHtmlSidebar::addFilter($text, $key, $options);
		}
		else {
			$entry = array(
				'label'		=> $text,
				'key'		=> $key,
				'options'	=> $options,
				'type'		=> $type
			);
			array_push(self::$entries, $entry);
		}
	}

	public static function render() 
	{
		$return  = JHtmlSidebar::render();

		if (count(self::$entries) > 0 ) {
			$return .= '<div class="filter-custom hidden-phone">';
			foreach(self::$entries as $entry) {
				switch( $entry['type'] ) {
					case 'calendar':
						$return .= '<div><center>'.JHtml::calendar($entry['options'], $entry['key'], $entry['key'], '%Y-%m-%d', array('class'=>'input input-medium', 'placeholder'=>$entry['label'])).'</center></div>';
					break;
					case 'calendar_btn':
						$return .= '<center><div class="rsmem_calendar_btn"><button type="button" class="hasTip btn btn-warning pull-right" title="'.JText::_('JSEARCH_FILTER_CLEAR').'" onclick="document.getElementById(\''.$entry['options']['to_btn'].'\').value=\'\';document.getElementById(\''.$entry['options']['from_btn'].'\').value=\'\';this.form.submit();"><i class="icon-remove"></i></button>
						<button type="submit" class="hasTip btn btn-info pull-right" title="'.$entry['key'].'">'.$entry['label'].'</button></div></center>';
					break;
				}
			}
			$return .= '</div> <hr class="hr-condensed">';
		}

		return $return;
	}
}
