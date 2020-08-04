<?php
/**
 * @version     1.0.0
 * @package     com_staffs
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      360South Pty Ltd <tech@360south.com.au> - http://www.360south.com.au/
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Staffs helper.
 */
class StaffsHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
        
		
		JHtmlSidebar::addEntry(
			JText::_('COM_STAFFS_TITLE_ITEMS'),
			'index.php?option=com_staffs&view=items',
			$vName == 'items'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_STAFFS_TITLE_CITEMS'),
			'index.php?option=com_staffs&view=citems',
			$vName == 'citems'
		);


	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_staffs';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
