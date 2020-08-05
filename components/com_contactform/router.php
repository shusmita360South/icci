<?php
/**
 * @version     1.0.0
 * @package     com_news
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

/**
 * @param	array	A named array
 * @return	array
 */
function ContactformBuildRoute(&$query)
{
	$segments = array();
    if(isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    };
    if(isset($query['catid'])) {
        list($id, $str) = explode(':', $query['catid'], 2);
        $segments[] = '';
        $segments[] = $str;
        unset( $query['catid'] );
    };
	if( isset($query['id'])) {
        @list($id, $str) = explode(':', $query['id'], 2);
        $segments[] = $id;
        $segments[] = $str;
        unset( $query['id'] );
    };
	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/banners/task/id/Itemid
 *
 * index.php?/banners/id/Itemid
 */
function ContactformParseRoute($segments)
{
	$vars = array();
	$app =& JFactory::getApplication();
	$menu =& $app->getMenu();
	$item =& $menu->getActive();
	$count = count( $segments );
	$id    = @explode(':',$segments[$count-2]);
	
	$vars['id']   = (int) $id[0];
	switch( $segments[0] ) {
		case 'form':
			$vars['view'] = 'form';
			break;
	}
	return $vars;
}
