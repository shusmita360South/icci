<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class RSMembershipHelper
{
	// @deprecated
	public static function getModulesWhere() {
		return '';
	}

	public static function isJ32() {
		return version_compare(JVERSION, '3.2', '>=');
	}

	public static function isJ3() {
		return version_compare(JVERSION, '3.0', '>=');
	}
	
	public static function isJ16() {
		return true;
	}

	public static function readConfig($force=false)
	{
		$config = RSMembershipConfig::getInstance();
		
		if ($force) {
			$config->reload();
		}
		
		return $config->getData();
	}
	
	public static function getConfig($name = null, $default = false)
	{
		$config = RSMembershipConfig::getInstance();
		if (is_null($name)) {
			return $config->getData();
		} else {
			return $config->get($name,$default);
		}
	}

	public static function showDate($date=null, $date_format=null) {
		if (is_null($date)) {
			$date = JFactory::getDate()->toSql();
		}
		
		if (is_null($date_format)) {
			$date_format = RSMembershipHelper::getConfig('date_format');
		}
		return JHtml::date($date, $date_format);
	}

	public static function sendMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null, $attachmentsString = null)
	{
		try {
			$mailer = JFactory::getMailer();
			
			if (RSMembershipHelper::getConfig('footer_enable')) {
				$replacements = array(
					'{sitename}'	=> JFactory::getConfig()->get('sitename'),
					'{siteurl}' 	=> JUri::root()
				);
				
				$footer = RSMembershipHelper::getConfig('footer_content');
				if (!$mode) {
					$footer = "\r\n".strip_tags($footer);
				}
				
				$body .= str_replace(array_keys($replacements), array_values($replacements), $footer);
			}
			
			// Handle multiple emails
			if (strpos($recipient, ',') !== false) {
				jimport('joomla.mail.helper');
				
				$emails 	= explode(',', $recipient);
				$recipient 	= array();
				foreach ($emails as $email) {
					$email = trim($email);
					if (JMailHelper::isEmailAddress($email)) {
						$recipient[] = $email;
					}
				}
			}

			if (!empty($attachmentsString))
			{
				if (!is_array($attachmentsString))
				{
					$attachmentsString = array($attachmentsString);
				}

				foreach ($attachmentsString as $attachment_data)
				{
					// if there is no filename or content skip the attachment
					if (empty($attachment_data->filename) || empty($attachment_data->content))
					{
						continue;
					}

					$mailer->addStringAttachment($attachment_data->content, $attachment_data->filename);
				}
			}
			
			return $mailer->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
		} catch (Exception $e) {
			return false;
		}
	}

	public static function getPriceFormat($price, $currency=null)
	{
        if (!$currency)
        {
            $currency = RSMembershipHelper::getConfig('currency');
        }

		$price = number_format($price,
            RSMembershipHelper::getConfig('format_decimals', 2),
            RSMembershipHelper::getConfig('format_decimals_point', '.'),
            RSMembershipHelper::getConfig('format_thousands_sep', '')
        );
		$format = RSMembershipHelper::getConfig('price_format');
		
		if (RSMembershipHelper::getConfig('price_show_free') && (empty($price) || $price == '0.00'))
		{
            return JText::_('COM_RSMEMBERSHIP_FREE');
        }
		
		return str_replace(array('{price}', '{currency}'), array($price, $currency), $format);
	}
	
	public static function buildGrandTotal(){
		static $loaded;

		if (is_null($loaded)) {
			$currency = RSMembershipHelper::getConfig('currency');

			JText::script('COM_RSMEMBERSHIP_FREE');
			$inline_js = "\n" . 'RSMembership.utils.format_decimals = ' . self::htmlEscape(RSMembershipHelper::getConfig('format_decimals', 2));
			$inline_js .= "\n" . 'RSMembership.utils.format_decimals_point = "' . self::htmlEscape(RSMembershipHelper::getConfig('format_decimals_point', '.')) . '"';
			$inline_js .= "\n" . 'RSMembership.utils.format_thousands_sep = "' . self::htmlEscape(RSMembershipHelper::getConfig('format_thousands_sep', '')) . '"';
			$inline_js .= "\n" . 'RSMembership.buildTotal.format = "' . self::htmlEscape(RSMembershipHelper::getConfig('price_format')) . '"';
			$inline_js .= "\n" . 'RSMembership.buildTotal.show_free = ' . (RSMembershipHelper::getConfig('price_show_free') ? true : false);
			$inline_js .= "\n" . 'RSMembership.buildTotal.currency = "' . self::htmlEscape($currency) . '"';
			$inline_js .= "\n" . 'jQuery(document).ready(function(){
									RSMembership.buildTotal.remake_total();
								});';
			JFactory::getDocument()->addScriptDeclaration($inline_js);
			$loaded = true;
		}
	}

	public static function htmlEscape($val)
	{
		return htmlentities($val, ENT_QUOTES, 'UTF-8');
	}

	public static function createThumb($src, $dest, $thumb_w, $type='jpg')
	{
		jimport('joomla.filesystem.file');

		$dest = $dest.'.'.$type;
		
		// load image
		$img = imagecreatefromjpeg($src);
		if ($img)
		{
			// get image size
			$width = imagesx($img);
			$height = imagesy($img);

			// calculate thumbnail size
			$new_width = $thumb_w;
			$new_height = floor($height*($thumb_w/$width));

			// create a new temporary image
			$tmp_img = imagecreatetruecolor($new_width, $new_height);

			// copy and resize old image into new image
			imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			// save thumbnail into a file
			imagejpeg($tmp_img, $dest);
			return true;
		}
		else
			return false;
	}

	public static function parseParams($params)
	{
		$return = array();
		
		$params = explode(';', $params);
		foreach ($params as $param)
		{
			$param = explode('=', $param);
			if ($param[0] == 'extras')
				$param[1] = explode(',', $param[1]);
				
			$return[$param[0]] = @$param[1];
		}
		
		return $return;
	}
	
	public static function getCache()
	{
		$return = new stdClass();
		
		$return->memberships = array();
		$db = JFactory::getDBO();
		$db->setQuery("SELECT `id`, `name` FROM #__rsmembership_memberships");
		$result = $db->loadObjectList();
		foreach ($result as $row)
			$return->memberships[$row->id] = $row->name;
		
		$return->extra_values = array();
		$db->setQuery("SELECT `id`, `name` FROM #__rsmembership_extra_values");
		$result = $db->loadObjectList();
		foreach ($result as $row)
			$return->extra_values[$row->id] = $row->name;
		
		return $return;
	}

	public static function getUserSubscriptions($user_id=null) {
		// Get the logged in user
		if (is_null($user_id)) {
			$user 		= JFactory::getUser();
			$user_id 	= $user->id;
		}

		// Get his subscribed memberships
		$memberships = array();
		$extras 	 = array();

		if ($user_id) {
			// Get the database object
			$db 	= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select($db->qn('membership_id'))
				  ->select($db->qn('extras'))
				  ->from($db->qn('#__rsmembership_membership_subscribers'))
				  ->where($db->qn('user_id').' = '.$db->q($user_id))
				  ->where($db->qn('status').' = '.$db->q(MEMBERSHIP_STATUS_ACTIVE));
			$db->setQuery($query);
			$results = $db->loadObjectList();

			if ($results) {
				foreach ($results as $result) {
					$memberships[] = $result->membership_id;

					if ($result->extras) {
						$extra 	= explode(',', $result->extras);
						$extras = array_merge($extras, $extra);
					}
				}
			}
		}

		return array($memberships, $extras);
	}

	public static function getShared($type=null, $search=null)
	{
		// Get the database object
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		// membership shared 
		$query
			->select($db->qn('s.membership_id'))
			->select($db->qn('s.params'))
			->select($db->qn('s.type'))
			->from($db->qn('#__rsmembership_membership_shared', 's'))
			->join('left', $db->qn('#__rsmembership_memberships', 'm').' ON '.$db->qn('s.membership_id').' = '.$db->qn('m.id'))
			->where($db->qn('s.published').' = '.$db->q('1'))
			->where($db->qn('m.published').' = '.$db->q('1'));

		if ($type) 
		{
			if ( is_array($type) ) 
				$query->where($db->qn('s.type').' IN ('.RSMembershipHelper::quoteImplode($type).')');
			else 
				$query->where($db->qn('s.type').' = '.$db->q($type));
		}
		if ( $search ) 
			$query->where($db->qn('s.params').' LIKE '.$db->q($search));

		$db->setQuery($query);
		$shared = $db->loadObjectList();

		// extra values shared
		$query->clear();
		$query
			->select($db->qn('s.extra_value_id'))
			->select($db->qn('s.params'))
			->select($db->qn('s.type'))
			->from($db->qn('#__rsmembership_extra_value_shared', 's'))
			->join('left', $db->qn('#__rsmembership_extra_values', 'v').' ON '.$db->qn('s.extra_value_id').' = '.$db->qn('v.id'))
			->where($db->qn('s.published').' = '.$db->q('1'))
			->where($db->qn('v.published').' = '.$db->q('1'));

		if ($type) 
		{
			if (is_array($type))
				$query->where($db->qn('s.type').' IN ('.RSMembershipHelper::quoteImplode($type).')');
			else
				$query->where($db->qn('s.type').' = '.$db->q($type));
		}
		if ( $search ) 
			$query->where($db->qn('s.params').' LIKE '.$db->q($search));

		$db->setQuery($query);
		$shared2 = $db->loadObjectList();

		if (!empty($shared2)) 
			$shared = array_merge($shared, $shared2);

		return $shared;
	}
	
	public static function checkContent(&$has_access, &$found_shared, &$redirect, $memberships, $extras)
	{
		// Get the database object
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$shared = RSMembershipHelper::getShared(array('article', 'category'));
		$view 	= JFactory::getApplication()->input->get('view', '', 'cmd');
		$id   	= JFactory::getApplication()->input->get('id', 0, 'int');

		if ($view == 'article')
		{
			$categories = RSMembershipHelper::_getItemCategories($id);

			foreach ( $shared as $share ) 
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';

				if (($share->type == 'article' && $share->params == $id) || ($share->type == 'category' && in_array($share->params, $categories)))
				{
					$found_shared = true;
					
					// Found a membership that shares this article
					if (!empty($where) && in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$query->clear();
						$query->select($db->qn('share_redirect'))->from($db->qn($table))->where($db->qn('id').' = '.$db->q($share->{$what}));
						$db->setQuery($query);
						$redirect = $db->loadResult();
					}
				}
			}
		}
		elseif ($view == 'category')
		{
			$categories = RSMembershipHelper::_getCategoryParents($id);
			array_push($categories, $id);

			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';

				if ($share->type == 'category' && in_array($share->params, $categories))
				{
					$found_shared = true;

					// Found a membership that shares this article
					if (!empty($where) && in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$query->clear();
						$query->select($db->qn('share_redirect'))->from($db->qn($table))->where($db->qn('id').' = '.$db->q($share->{$what}));
						$db->setQuery($query);
						$redirect = $db->loadResult();
					}
				}
			}
		}
	}
	
	public static function _getItemCategories($id)
	{
		// Get the database object
		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT `catid` FROM #__content WHERE `id`='".(int) $id."'");
		$catid = $db->loadResult();
		$categories = RSMembershipHelper::_getCategoryParents($catid);
		array_push($categories, $catid);

		return $categories;
	}
	
	public static function _getCategoryParents($catid)
	{
		$db = JFactory::getDBO();
		
		$categories = array();
		$db->setQuery("SELECT `parent_id` FROM #__categories WHERE `extension`='com_content' AND `id`='".(int) $catid."'");
		while ($catid = $db->loadResult())
		{
			if ($catid == 1) break;
			$categories[] = $catid;
			$db->setQuery("SELECT `parent_id` FROM #__categories WHERE `extension`='com_content' AND `id`='".(int) $catid."'");
		}
		
		return $categories;
	}

	public static function checkURL(&$has_access, &$found_shared, &$redirect, $memberships, $extras)
	{
		$option    = RSMembershipHelper::getOption();
		$app 	   = JFactory::getApplication();
		$type      = $app->isClient('administrator') ? 'backendurl' : 'frontendurl';
		$shared    = RSMembershipHelper::getShared($type, $option.'%');

		// Get the database object
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		if ( !empty($shared) ) 
		{		
			foreach ( $shared as $share ) 
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';

				$url = RSMembershipHelper::parseQuery($share->params);

				$current_query = array();
				foreach ($url as $q => $value)
				{
					$var = JFactory::getApplication()->input->get($q, false, 'string');
					if ($var !== false)
						$current_query[] = $q.'='.$var;
				}
				$current_query = $option.(!empty($current_query) ? '&'.implode('&', $current_query) : '');
				
				if ($current_query == $share->params || RSMembershipHelper::_is_match($current_query, $share->params))
				{
					$found_shared = true;

					if (in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$query->clear();
						$query->select($db->qn('share_redirect'))->from($db->qn($table))->where($db->qn('id').' = '.$db->q($share->{$what}));
						$db->setQuery($query);
						$redirect = $db->loadResult();
					}
				}
			}
		}
	}
	
	public static function checkMenu(&$has_access, &$found_shared, &$redirect, $memberships, $extras)
	{
		$Itemid = JFactory::getApplication()->input->get('Itemid', 0, 'int');
		$shared = RSMembershipHelper::getShared('menu', $Itemid);

		// Get the database object
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		if ( !empty($shared) ) 
		{
			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';

				if ($share->params == $Itemid)
				{
					$found_shared = true;
					if (in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$query->clear();
						$query->select($db->qn('share_redirect'))->from($db->qn($table))->where($db->qn('id').' = '.$db->q($share->{$what}));
						$db->setQuery($query);

						$redirect = $db->loadResult();
					}
				}
			}
		}
	}
	
	public static function getOption()
	{
		$option = JFactory::getApplication()->input->get('option', '', 'cmd');
		return $option;
	}
	
	public static function checkShared() 
	{
		$option = RSMembershipHelper::getOption();
		if ( !$option ) 
			return;
		
		$app = JFactory::getApplication();
		
		// Get the language
		$lang = JFactory::getLanguage();
		$lang->load('com_rsmembership');
		$msg = JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_NEED_SUBSCRIPTION');

		list($memberships, $extras) = RSMembershipHelper::getUserSubscriptions();
		
		$has_access   = false;
		$found_shared = false;
		$redirect 	  = '';
		
		if (!$app->isClient('administrator'))
		{
			// Check the articles, categories and sections
			if ($option == 'com_content') RSMembershipHelper::checkContent($has_access, $found_shared, $redirect, $memberships, $extras);
			// Menu - Itemid
			RSMembershipHelper::checkMenu($has_access, $found_shared, $redirect, $memberships, $extras);
		}
		
		$instances = RSMembership::getSharedContentPlugins();
		foreach ($instances as $instance)
			if (method_exists($instance, 'checkShared'))
				$instance->checkShared($option, $has_access, $found_shared, $redirect, $memberships, $extras);
		
		// Custom URL
		RSMembershipHelper::checkURL($has_access, $found_shared, $redirect, $memberships, $extras);
		
		if (!$found_shared)
			$has_access = true;
		
		if ($found_shared && $has_access) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsmembership/tables');
			$row 			= JTable::getInstance('Log','RSMembershipTable');
			$row->date 		= JFactory::getDate()->toSql();
			$row->user_id 	= JFactory::getUser()->get('id');
			$row->path 		= '[URL] '.JUri::getInstance()->toString();
			$row->ip 		= RSMembershipHelper::getIP();
			$row->store();
		}
		
		if (!$has_access)
		{
			$redirect = empty($redirect) ? JUri::root() : JRoute::_($redirect, false);
			$app->redirect($redirect, $msg);
		}
	}

	public static function getIP()
    {
        if (RSMembershipHelper::getConfig('store_ip'))
        {
            return JFactory::getApplication()->input->server->getString('REMOTE_ADDR');
        }
        else
        {
            return '0.0.0.0';
        }
    }
	
	public static function _is_match($url, $pattern)
	{
		$pattern = RSMembershipHelper::_transform_string($pattern);	
		preg_match_all($pattern, $url, $matches);
		
		return (!empty($matches[0]));
	}

	public static function _transform_string($string)
	{
		$string = preg_quote($string, '/');
		$string = str_replace(preg_quote('{*}', '/'), '(.*)', $string);	
		
		$pattern = '#\\\{(\\\\\?){1,}\\\}#';
		preg_match_all($pattern, $string, $matches);
		if (count($matches[0]) > 0)
			foreach ($matches[0] as $match)
			{
				$count = count(explode('\?', $match)) - 1;
				$string = str_replace($match, '(.){'.$count.'}', $string);
			}
		
		return '#'.$string.'#';
	}
	
	public static function parseQuery($query) 
	{
		$return = array();

		$query = explode('&', $query);
		unset($query[0]);
		foreach ($query as $q)
		{
			$new = explode('=', $q);
			$return[$new[0]] = @$new[1];
		}
		
		return $return;
	}

	public static function getCategoryName($id) {
		static $categories = array();

		if (!isset($categories[$id])) {
			// No category workaround
			if (!$id) {
				$categories[0] = JText::_('COM_RSMEMBERSHIP_NO_CATEGORY');
			}

			// We have a numeric ID - grab the information from the database
			if (is_numeric($id) && $id > 0) {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select($db->qn('name'))
					->from($db->qn('#__rsmembership_categories'))
					->where($db->qn('id') . '=' . $db->q($id));

				$categories[$id] = $db->setQuery($query)->loadResult();
			}
		}

		return $categories[$id];
	}

	public static function showCustomField($field, $selected=array(), $editable=true, $show_required=true, $type='user')
	{
		if (empty($field) || empty($field->type)) return false;
		
		$name_field = ( $type=='user' ? 'rsm_fields' : 'rsm_membership_fields');

		$return = array();
		$return[0] = '<label title="'.JText::_($field->label).'" class="hasTooltip" for="rsm_'.$field->name.'" id="jform_'.$field->name.'-lbl">'.JText::_($field->label).'</label>';
		$return[1] = '';
		switch ($field->type)
		{
			case 'hidden':
				$name = $name_field.'['.$field->name.']';
				
				$app = JFactory::getApplication();
				
				if ($app->isClient('administrator') && isset($selected[$field->name])) {
					$field->values = $selected[$field->name];
				} else {
					$field->values = RSMembershipHelper::isCode($field->values);
				}
				$return[1] = '<input type="hidden" name="'.$name.'" id="rsm_'.$field->name.'" value="'.htmlspecialchars($field->values, ENT_COMPAT, 'utf-8').'" />';
				
				if ($editable && $app->isClient('administrator')) {
					$return[1] = '<input type="text" class="rsm_textbox" name="'.$name.'" id="rsm_'.$field->name.'" value="'.htmlspecialchars($field->values, ENT_COMPAT, 'utf-8').'" size="40" />';
				}
				else if(!$editable && $app->isClient('administrator')) {
					$return[1] = htmlspecialchars($field->values, ENT_COMPAT, 'utf-8');
				}
				
				if (!$app->isClient('administrator')) {
					$return[2] = 'hidden';
				}
			break;

			case 'freetext':
				$field->values = RSMembershipHelper::isCode($field->values);
				$return[1] = $field->values;
			break;

			case 'textbox':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = RSMembershipHelper::isCode($field->values);

				$name = $name_field.'['.$field->name.']';

				$return[1] = '<input type="text" name="'.$name.'" id="rsm_'.$field->name.'" value="'.htmlspecialchars($field->values, ENT_COMPAT, 'utf-8').'" size="40" '.RSMembershipHelper::addClass($field->additional, 'rsm_textbox').' />';
				
				if (!$editable)
					$return[1] = htmlspecialchars($field->values, ENT_COMPAT, 'utf-8');
			break;
			
			case 'textarea':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = RSMembershipHelper::isCode($field->values);
				
				$name = $name_field.'['.$field->name.']';

				$return[1] = '<textarea name="'.$name.'" id="rsm_'.$field->name.'" '.RSMembershipHelper::addClass($field->additional, 'textarea rsm_textarea').'>'.htmlspecialchars($field->values, ENT_COMPAT, 'utf-8').'</textarea>';
				
				if (!$editable)
					$return[1] = nl2br(htmlspecialchars($field->values, ENT_COMPAT, 'utf-8'));
			break;
			
			case 'select':
			case 'multipleselect':
				$field->values = RSMembershipHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				$multiple = $field->type == 'multipleselect' ? 'multiple="multiple"' : '';
				
				$name = $name_field.'['.$field->name.'][]';
				
				if ($editable)
				{
					$return[1] = '<select '.$multiple.' name="'.$name.'" id="rsm_'.$field->name.'" '.RSMembershipHelper::addClass($field->additional, 'rsm_select').'>';
						foreach ($field->values as $value)
						{
							$tmp 	= explode('|', $value, 2);
							$label 	= isset($tmp[1]) ? $tmp[1] : $tmp[0];
							$val   	= $tmp[0];
							
							$found_checked = false;
							if (preg_match('/\[c\]/',$value))
							{
								$value 	= str_replace('[c]', '', $value);
								$label 	= str_replace('[c]', '', $label);
								$val 	= str_replace('[c]', '', $val);
								$found_checked = true;
							}
							
							$checked = '';
							if (isset($selected[$field->name]) && in_array($val, $selected[$field->name]))
								$checked = 'selected="selected"';
							elseif (!isset($selected[$field->name]) && $found_checked)
								$checked = 'selected="selected"';
							
							$return[1] .= '<option '.$checked.' value="'.htmlspecialchars($val, ENT_COMPAT, 'utf-8').'">'.htmlspecialchars($label, ENT_COMPAT, 'utf-8').'</option>';
						}
					$return[1] .= '</select>';
				}
				else
				{
					$return[1] = '';
					if (isset($selected[$field->name]))
					{
						foreach ($field->values as $value)
						{
							$value 	= str_replace('[c]', '', $value);
							
							$tmp 	= explode('|', $value, 2);
							$label 	= isset($tmp[1]) ? $tmp[1] : $tmp[0];
							$val   	= $tmp[0];
							
							if ((is_array($selected[$field->name]) && in_array($val, $selected[$field->name]))
							|| (!is_array($selected[$field->name]) && $selected[$field->name] == $val))
								$return[1] .= htmlspecialchars($label, ENT_COMPAT, 'utf-8').'<br />';
						}
					}
				}
			break;
			
			case 'checkbox':
				$field->values = RSMembershipHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				if ($editable)
				{
					foreach ($field->values as $i => $value)
					{
						$tmp 	= explode('|', $value, 2);
						$label 	= isset($tmp[1]) ? $tmp[1] : $tmp[0];
						$val   	= $tmp[0];
							
						$found_checked = false;
						if (preg_match('/\[c\]/',$value))
						{
							$value  = str_replace('[c]', '', $value);
							$label 	= str_replace('[c]', '', $label);
							$val 	= str_replace('[c]', '', $val);
							$found_checked = true;
						}
						
						$checked = '';
						if (isset($selected[$field->name]) && in_array($val, $selected[$field->name]))
							$checked = 'checked="checked"';
						elseif (!isset($selected[$field->name]) && $found_checked)
							$checked = 'checked="checked"';
						
						$name = $name_field.'['.$field->name.'][]';
						
						$return[1] .= '<input '.$checked.' type="checkbox" name="'.$name.'" value="'.htmlspecialchars($val, ENT_COMPAT, 'utf-8').'" id="rsm_field_'.$field->id.'_'.$i.'" '.RSMembershipHelper::addClass($field->additional, 'pull-left rsm-margin-chradio').' /> <label for="rsm_field_'.$field->id.'_'.$i.'">'.$label.'</label>';
					}
				}
				else 
				{
					$return[1] = '';
					if (isset($selected[$field->name]))
					{
						foreach ($field->values as $value)
						{
							$value 	= str_replace('[c]', '', $value);
							
							$tmp 	= explode('|', $value, 2);
							$label 	= isset($tmp[1]) ? $tmp[1] : $tmp[0];
							$val   	= $tmp[0];
							
							if ((is_array($selected[$field->name]) && in_array($val, $selected[$field->name]))
							|| (!is_array($selected[$field->name]) && $selected[$field->name] == $val))
								$return[1] .= htmlspecialchars($label, ENT_COMPAT, 'utf-8').'<br />';
						}
					}
				}
			break;
			
			case 'radio':
				$field->values = RSMembershipHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				if ($editable)
				{
					foreach ($field->values as $i => $value)
					{
						$tmp 	= explode('|', $value, 2);
						$label 	= isset($tmp[1]) ? $tmp[1] : $tmp[0];
						$val   	= $tmp[0];
						
						$found_checked = false;
						if (preg_match('/\[c\]/',$value))
						{
							$value  = str_replace('[c]', '', $value);
							$label 	= str_replace('[c]', '', $label);
							$val 	= str_replace('[c]', '', $val);
							$found_checked = true;
						}
						
						$checked = '';
						if (isset($selected[$field->name]) && $selected[$field->name] == $val)
							$checked = 'checked="checked"';
						elseif (!isset($selected[$field->name]) && $found_checked)
							$checked = 'checked="checked"';
						
						$name = $name_field.'['.$field->name.']';
						
						$return[1] .= '<input '.$checked.' type="radio" name="'.$name.'" value="'.htmlspecialchars($val, ENT_COMPAT, 'utf-8').'" id="rsm_field_'.$field->id.'_'.$i.'" '.RSMembershipHelper::addClass($field->additional, 'pull-left rsm-margin-chradio').'/> <label for="rsm_field_'.$field->id.'_'.$i.'">'.$label.'</label>';
					}
				}
				else
				{
					$return[1] = '';
					if (isset($selected[$field->name]))
					{
						foreach ($field->values as $value)
						{
							$value 	= str_replace('[c]', '', $value);
							
							$tmp 	= explode('|', $value, 2);
							$label 	= isset($tmp[1]) ? $tmp[1] : $tmp[0];
							$val   	= $tmp[0];
							
							if ($selected[$field->name] == $val)
								$return[1] .= htmlspecialchars($label, ENT_COMPAT, 'utf-8').'<br />';
						}
					}
				}
			break;
			
			case 'calendar':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = RSMembershipHelper::isCode($field->values);
					
				$name = $name_field.'['.$field->name.']';
				
				$format = (!empty($field->datetimeformat) ? $field->datetimeformat : RSMembershipHelper::getConfig('date_format'));
				$format = RSMembershipHelper::getCalendarFormat($format);
				
				if ($editable)
					$return[1] = JHtml::_('calendar', $field->values, $name, 'rsm_'.$field->name, $format, $field->additional);
				else
					$return[1] = htmlspecialchars($field->values, ENT_COMPAT, 'utf-8');
			break;
		}

		if ($field->required && $editable && $show_required)
			$return[1] .= ' '.JText::_('COM_RSMEMBERSHIP_REQUIRED');
		
		return $return;
	}
	
	public static function addClass(&$attributes, $className)
	{
		if (preg_match('#class="(.*?)"#is', $attributes, $matches))
			$attributes = str_replace($matches[0], str_replace($matches[1], $matches[1].' '.$className, $matches[0]), $attributes);
		else
			$attributes .= ' class="'.$className.'"';
		
		return $attributes;
	}

	public static function isCode($value)
	{
		if (strpos($value,'//<code>') !== false)
			return eval($value);
		else
			return $value;
	}
	
	public static function getCalendarFormat($format)
	{
		$php = array( '%',  'D',  'l',  'M',  'F',  'd',  'j',  'H',  'h',  'z',  'G',  'g',  'm',  'i', "\n",  'A',  'a',  's',  'U', "\t",  'W',  'N',  'w',  'y',  'Y');
		$js  = array('%%', '%a', '%A', '%b', '%B', '%d', '%e', '%H', '%I', '%j', '%k', '%l', '%m', '%M', '%n', '%p', '%P', '%S', '%s', '%t', '%U', '%u', '%w', '%y', '%Y');
		
		return str_replace($php, $js, $format);
	}
	
	public static function getUserFields($user_id)
	{
		$return = array();
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__rsmembership_subscribers WHERE user_id='".(int) $user_id."'");
		$result = $db->loadObject();
		
		$fields = RSMembership::getCustomFields(array('published'=>1));
		foreach ($fields as $field)
		{
			$field_id = 'f'.$field->id;
			$return[$field->name] = isset($result->{$field_id}) ? $result->{$field_id} : '';
		}
		
		return $return;
	}
	
	public static function getTransactionMembershipFields($user_id, $transaction_id)
	{
		$db = JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select($db->qn('user_data'))
			->from($db->qn('#__rsmembership_transactions'))
			->where($db->qn('id').' = '.$db->q($transaction_id))
			->where($db->qn('user_id').' = '.$db->q($user_id));
		$db->setQuery($query);
		$result = $db->loadResult();
		
		$result = unserialize($result);
		$membership_fields = isset($result->membership_fields) ? $result->membership_fields : array();
		return $membership_fields;
	}
	
	public static function getExtrasNames($extras) {
		static $cache;
		
		if (!is_array($cache)) {
			$db 	= JFactory::getDbo();
			$query 	= $db->getQuery(true);
			
			$query->select($db->qn('id'))
				  ->select($db->qn('name'))
				  ->from($db->qn('#__rsmembership_extra_values'));
			
			$db->setQuery($query);
			$cache = $db->loadObjectList('id');
		}
		
		$return = array();
		if (!is_array($extras)) {
			$extras = explode(',', $extras);
		}
		foreach ($extras as $value) {
			if (isset($cache[$value])) {
				$return[] = $cache[$value]->name;
			}
		}
		
		return implode(', ', $return);
	}
	
	public static function getFields($editable=true, $user_id=false, $show_required=true, $transaction_id=0)
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$return = array();

		if ($user_id)
		{
			$user = JFactory::getUser($user_id);
			$guest = false;
		}
		else
		{
			$user = JFactory::getUser();
			$guest = $user->get('guest');
		}
		
		$post = JFactory::getApplication()->input->get('rsm_fields', array(), 'array');
		
		$fields = RSMembership::getCustomFields(array('published'=>1));

		if (!$post && !$guest)
		{
			if ($transaction_id)
			{
				$query->clear();
				$query->select($db->qn('user_data'))->from($db->qn('#__rsmembership_transactions'))->where($db->qn('id').' = '.$db->q($transaction_id));
				$db->setQuery($query);
				$data = unserialize($db->loadResult());

				if (!empty($data->fields)) {
                    foreach ($data->fields as $key => $val)
                        $post[$key] = $val;
                }
			}
			else
			{
				$query->clear();
				$query->select('*')->from($db->qn('#__rsmembership_subscribers'))->where($db->qn('user_id').' = '.$db->q($user->get('id')));
				$db->setQuery($query);
				$data = $db->loadObject();
				
				if (!empty($data))
				{
					unset($data->user_id);
					
					foreach ($fields as $field)
					{
						$field_id = 'f'.$field->id;
						if (!isset($data->$field_id))
							continue;
							
						if (in_array($field->type, array('select', 'multipleselect', 'checkbox')))
							$post[$field->name] = explode("\n", $data->$field_id);
						else
							$post[$field->name] = $data->$field_id;
					}
				}
			}
		}

		foreach ($fields as $field) {
			$return[] 	= RSMembershipHelper::showCustomField($field, $post, $editable, $show_required);
		}

		return $return;
	}
	
	public static function getMembershipFields($membership_id, $editable=true, $user_id=false, $show_required=true, $transaction_id=0)
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$return = array();

		if ($user_id)
		{
			$user = JFactory::getUser($user_id);
			$guest = false;
		}
		else
		{
			$user = JFactory::getUser();
			$guest = $user->get('guest');
		}
		
		$post = JFactory::getApplication()->input->get('rsm_membership_fields', array(), 'array');
		
		$fields = RSMembership::getCustomMembershipFields($membership_id);

		if (!$post && !$guest)
		{
			if ($transaction_id)
			{
				$query->clear();
				$query->select($db->qn('user_data'))->from($db->qn('#__rsmembership_transactions'))->where($db->qn('id').' = '.$db->q($transaction_id));
				$db->setQuery($query);
				$data = unserialize($db->loadResult());
				
				if (isset($data->membership_fields)) {
					foreach ($data->membership_fields as $key => $val)
						$post[$key] = $val;
				}
			}
		}
		
		foreach ($fields as $field) {
			$return[] 	= RSMembershipHelper::showCustomField($field, $post, $editable, $show_required, 'membership');
		}
		
		return $return;
	}
	
	protected static function filterFieldsValidation($fields) {
		if (empty($fields)) {
			return $fields;
		}
		foreach ($fields as $i => $field) {
			if (!$field->required && empty($field->rule)) {
				unset($fields[$i]);
			}
		}

		return $fields;
	}

	public static function getFieldsValidation($membership_id=null, $upgrade=null)
	{
		if (is_null($upgrade)) {
			$fields = RSMembership::getCustomFields(array('published'=>1));
			// check if the fields are required or have a validation rule set
			$fields = self::filterFieldsValidation($fields);

		} else {
			$fields = array();
		}

		if (!is_null($membership_id)) {
			$membership_fields = RSMembership::getCustomMembershipFields($membership_id, array('published'=>1));
			// check if the fields are required or have a validation rule set
			$membership_fields = self::filterFieldsValidation($membership_fields);
			$fields = array_merge($fields, $membership_fields);
		}

		$validations = array();
		foreach ($fields as $field)
		{
			$field_validation = new stdClass();

			if (!in_array($field->type, array('checkbox', 'radio'))) {
				// Add the field name
				$field_validation->field_id = 'rsm_' . $field->name;
			} else {
				$field->values = RSMembershipHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);

				$field_ids = array();
				foreach ($field->values as $i => $value)
				{

					$field_ids[] = 'rsm_field_'.$field->id.'_'.$i;
				}

				// In case there's no field ids found, skip it
				if (empty($field_ids)) {
					continue;
				}

				// Add the field name
				$field_validation->field_id = $field_ids;
			}

			// Add the field type
			$field_validation->field_type = $field->type;

			// Build the validation message
			$validation_message = JText::_($field->validation);
			// if the validation message is set skip the addition for the rules
			$no_default_msg = false;
			if (empty($validation_message)) {
				if ($field->required) {
					$validation_message = JText::sprintf('COM_RSMEMBERSHIP_VALIDATION_DEFAULT_ERROR', JText::_($field->label));
				}
				$no_default_msg = true;
			}
			$validation_message = str_replace(array("\r\n", "\r"), "\n", $validation_message);
			$validation_message = str_replace("\n", '\n', $validation_message);

			$field_validation->err_msg = JText::_($validation_message, true);

			// if the field has a validation rule defined
			if (isset($field->rule) && !empty($field->rule)) {
				$field_validation->rule = $field->rule;
				if ($no_default_msg) {
					$field_validation->err_msg_rule = JText::sprintf('COM_RSMEMBERSHIP_VALIDATION_DEFAULT_'.strtoupper($field->rule).'_ERROR', JText::_($field->label));
				}
			}

			// Set this so that we can determine in the javascript that this field is required
			$field_validation->required = $field->required;

			// Add the field validations to our validations
			$validations[] = $field_validation;
		}

		return !empty($validations) ? $validations : '';
	}
	
	// @todo must return JDate object
	// @todo date() change to JFactory::getDate()->format()
	public static function calculateFixedDate($day, $month, $year, $date=null)
	{
		$offset = JFactory::getApplication()->getCfg('offset');
		if (is_null($date)) {
			$date = JFactory::getDate('now', $offset);
		}
		
		if ($day == 0 && $month == 0 && $year == 0) {
			// Add a day
			$date->modify('+1 day');
			return $date;
		} elseif ($day > 0 && $month == 0 && $year == 0) {
			$month_days = gmdate('t',gmmktime(0, 0, 0, $date->format('m'), 1, $date->format('Y')));
			$expire_day = $day > $month_days ? $month_days : $day;

			// If we didn't pass the expiry day, expire this month, else expire next month
			$month = $date->format('d') < $expire_day ? $date->format('m') : $date->format('m') + 1;
			$year  = $date->format('Y');

			$month_days = gmdate('t',gmmktime(0, 0, 0, $month, 1, $year));
			if ($day > $month_days) {
				$day = $month_days;
			}
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		} elseif ($day == 0 && $month > 0 && $year == 0) {
			$day  = $date->format('m') == $month ? $date->format('d') + 1 : 1;
			$year = $date->format('Y');
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		} elseif ($day == 0 && $month == 0 && $year >  0) {
			$month = $date->format('m');
			$day   = $date->format('d') + 1;
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		} elseif ($day >  0 && $month >  0 && $year == 0) {
			$month_days = gmdate('t',gmmktime(0, 0, 0, $month, 1, $date->format('Y')));
			$expire_day = $day > $month_days ? $month_days : $day;

			// If we didn't pass the expiry day and month, expire this year, else expire next year
			$year = $date->format('m') < $month || ($date->format('d') < $expire_day && $date->format('m') == $month) ? $date->format('Y') : $date->format('Y') + 1;

			$month_days = gmdate('t',gmmktime(0, 0, 0, $month, 1, $year));
			if ($day > $month_days) {
				$day = $month_days;
			}

			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		} elseif ($day == 0 && $month >  0 && $year >  0) {
			$day  = $date->format('m') == $month && $date->format('Y') == $year ? $date->format('d') + 1 : 1;
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		} elseif ($day >  0 && $month == 0 && $year >  0) {
			$month_days = gmdate('t',gmmktime(0, 0, 0, $date->format('m'), 1, $date->format('Y')));
			$expire_day = $day > $month_days ? $month_days : $day;

			// If we didn't pass the expiry day, expire this month, else expire next month
			$month = $date->format('d') < $expire_day ? $date->format('m') : $date->format('m') + 1;
			// If we've passed onto the next year, decrement it (or else we'll be expiring +1 year)
			if ($month > 12) {
				$year--;
			}

			// If we haven't reached this year, set the month to January since that's the earliest expiry date.
			if ($date->format('Y') < $year) {
				$month = 1;
			}

			$month_days = gmdate('t',gmmktime(0, 0, 0, $month, 1, $year));
			if ($day > $month_days) {
				$day = $month_days;
			}

			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		} elseif ($day >  0 && $month > 0 && $year >  0) {
			// Expire on a fixed date
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		}
		$sqlDate = gmdate('Y-m-d H:i:s', $membership_end);
		return JFactory::getDate($sqlDate, $offset);
	}

	public static function getStatusesList($list=true)
	{
		$return = array();
		for ($i=0;$i<=3;$i++)
		{
			if ($list)
				$return[] = JHtml::_('select.option', $i, JText::_('COM_RSMEMBERSHIP_STATUS_'.$i));
			else
				$return[$i] = JText::_('COM_RSMEMBERSHIP_STATUS_'.$i);
		}
		
		return $return;
	}

	public static function getMembershipsList($list=true) 
	{
		$options = array();
		$db 	 = JFactory::getDBO();
		$query	 = $db->getQuery(true);

		$query->select($db->qn('id').', '.$db->qn('name'))->from($db->qn('#__rsmembership_categories'))->order($db->qn('ordering').' ASC');
		$db->setQuery($query);
		$categories  = $db->loadObjectList();

		// add no category
		$no_category  		= new stdClass();
		$no_category->id 	= 0;
		$no_category->name 	= JText::_('COM_RSMEMBERSHIP_NO_CATEGORY');
		array_unshift($categories, $no_category);

		$query->clear();
		$query->select($db->qn('id').', '.$db->qn('name').', '.$db->qn('category_id'))->from($db->qn('#__rsmembership_memberships'))->order($db->qn('ordering').', '.$db->qn('ordering').' ASC');
		$db->setQuery($query);
		$memberships = $db->loadObjectList();

		foreach ($categories as $category)
		{
			if ($list) 
				$options[] = (object) array( 'value' => '<OPTGROUP>', 'text' => $category->name);

			foreach ($memberships as $membership)
			{
				if ($membership->category_id != $category->id) continue;

				if ($list) 
					$options[] = JHtml::_('select.option', $membership->id, $membership->name);
				else
					$options[$membership->id] = $membership->name;
			}

			if ($list) 
				$options[] = (object) array( 'value' => '</OPTGROUP>', 'text' => $category->name);
		}

		return $options;
	}
	
	public static function buildHead() 
	{
		JHtml::_('jquery.framework', true);
		JHtml::_('stylesheet', 'com_rsmembership/admin/style.css', array('relative' => true, 'version' => 'auto'));
		JHtml::_('script', 'com_rsmembership/admin/script.js', array('relative' => true, 'version' => 'auto'));
	}

	// @deprecated
	public static function sendExpirationEmails() {}
	
	public static function quoteImplode($array) {
		static $db;

		if (!$db) {
			$db = JFactory::getDBO();
		}

		if (is_array($array)) {
			$quoted = array();
			foreach ($array as $value) {
				$quoted[] = $db->q($value);
			}
			return implode(',', $quoted);
		}

		return $array;
	}

	public static function renderMagnificPopup($id = null, $args = array()) {
		if (is_null($id)) {
			return '';
		}

		if (!isset($args['url']) || (!is_bool($args['url']) && strlen($args['url']) == 0) || (is_bool($args['url']) && $args['url'] === true)) {
			return '';
		}

		if (!isset($args['title']) || strlen($args['title']) == 0) {
			$title = '';
		} else {
			$title = '<h3 class="magnific-title">'.$args['title'].'</h3>';
		}

		static $loadFiles;
		if (is_null($loadFiles)) {
			JHtml::_('jquery.framework', true);
			JHtml::_('script', 'com_rsmembership/jquery.magnific-popup.min.js', array('relative' => true, 'version' => 'auto'));
			JHtml::_('stylesheet', 'com_rsmembership/magnific-popup.css', array('relative' => true, 'version' => 'auto'));

			JText::script('COM_RSMEMBERSHIP_JQUERY_NOT_FOUND');

			$loadFiles = true;
		}

		if (!isset($args['height'])) {
			$args['height'] = 400;
		}

		/**
		 * - if the url is "false" and there is specified a default content then the modal body will have this content, otherwise it will be empty
		 * - if the url is a defined url then the iframe will be loaded
		 */
		if (is_bool($args['url']) && !$args['url']) {
			$current_body = isset($args['default_content']) && strlen($args['default_content']) ? $args['default_content'] : '';
		} else {
			$current_body = '<iframe style="height: '.$args['height'].'px;" src="'.$args['url'].'"></iframe>';
		}

		$modal_html = '
			<div id="'.$id.'" class="rsmembership-magnific-popup mfp-hide">
				<div class="magnific-header">'.$title.'</div>
				<div class="magnific-popup-body">
					'.$current_body.'
				</div>
				<button title="'.JText::_('COM_RSMEMBERSHIP_CLOSE').'" type="button" class="mfp-close">&times;</button>
			</div>';

		return $modal_html;
	}

	public static function anonymise($id, $anonymiseJoomlaData = 1)
    {
        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);

        // Let's create a fake email & fake username
        $fake_email     = JUserHelper::genRandomPassword(mt_rand(10, 16)) . '@' . JUserHelper::genRandomPassword(mt_rand(10, 16));
        $fake_username  = JUserHelper::genRandomPassword(mt_rand(10, 16));

        if ($anonymiseJoomlaData)
        {
            // Make sure this email is free
            $query->clear()
                ->select($db->qn('id'))
                ->from($db->qn('#__users'))
                ->where($db->qn('email') . ' = ' . $db->q($fake_email));
            while ($db->setQuery($query)->loadResult())
            {
                $fake_email .= JUserHelper::genRandomPassword(mt_rand(1, 2));
                $query->clear()
                    ->select($db->qn('id'))
                    ->from($db->qn('#__users'))
                    ->where($db->qn('email') . ' = ' . $db->q($fake_email));
            }

            // Make sure this username is free
            $query->clear()
                ->select($db->qn('id'))
                ->from($db->qn('#__users'))
                ->where($db->qn('username') . ' = ' . $db->q($fake_username));
            while ($db->setQuery($query)->loadResult())
            {
                $fake_username .= JUserHelper::genRandomPassword(mt_rand(1, 2));
                $query->clear()
                    ->select($db->qn('id'))
                    ->from($db->qn('#__users'))
                    ->where($db->qn('username') . ' = ' . $db->q($fake_username));
            }

            // #__users data
            $query->clear()
                ->update($db->qn('#__users'))
                ->set($db->qn('name') . ' = ' . $db->q($fake_username))
                ->set($db->qn('username') . ' = ' . $db->q($fake_username))
                ->set($db->qn('email') . ' = ' . $db->q($fake_email))
                ->set($db->qn('password') . ' = ' . $db->q(JUserHelper::hashPassword(JUserHelper::genRandomPassword(20))))
                ->where($db->qn('id') . ' = ' . $db->q($id));
            $db->setQuery($query)->execute();
        }

        // Custom fields
        if ($fields = RSMembership::getCustomFields())
        {
            $query->clear()
                ->update($db->qn('#__rsmembership_subscribers'))
                ->where($db->qn('user_id') . ' = ' . $db->q($id));
            foreach ($fields as $field)
            {
                $value = JUserHelper::genRandomPassword(mt_rand(8, 32));
                $query->set($db->qn('f' . $field->id) . ' = ' . $db->q($value));
            }

            $db->setQuery($query)->execute();
        }

        // Transactions
        $query->clear()
            ->update($db->qn('#__rsmembership_transactions'))
            ->set($db->qn('user_email') . ' = ' . $db->q('0.0.0.0'))
            ->set($db->qn('user_data') . ' = ' . $db->q(serialize(new stdClass())))
            ->set($db->qn('ip') . ' = ' . $db->q('0.0.0.0'))
            ->set($db->qn('user_email') . ' = ' . $db->q($fake_email))
            ->where($db->qn('user_id') . ' = ' . $db->q($id));
        $db->setQuery($query)->execute();

        // Access logs
        $query->clear()
            ->update($db->qn('#__rsmembership_logs'))
            ->set($db->qn('ip') . ' = ' . $db->q('0.0.0.0'))
            ->where($db->qn('user_id') . ' = ' . $db->q($id));
        $db->setQuery($query)->execute();
    }
}