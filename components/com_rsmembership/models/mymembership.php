<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipModelMymembership extends JModelItem
{
	public 		$_html = '';
	protected 	$_data = null;
	protected 	$_folder = null;
	protected 	$_parents = array();
	protected 	$_extra_parents = array();
	protected 	$_parent = 0;
	protected 	$transaction_id = 0;

	protected 	$context = 'com_rsmembership.mymembership';

	public		$terms;
	
	protected 	$user;
	protected 	$isWindows;
	
	public function __construct()
	{
		parent::__construct();
		jimport('joomla.filesystem.folder');

		// Some workarounds are needed for Windows
		$this->isWindows = DIRECTORY_SEPARATOR == '\\';
		
		// Get logged in user
		$this->user = JFactory::getUser();
		
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$db 	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		// Not logged in - must redirect to login.
		if ($this->user->guest) {
			$link = base64_encode((string) JUri::getInstance());
			$app->redirect(JRoute::_('index.php?option=com_users&view=login&return='.$link, false));
		}
		
		// Membership doesn't match - redirect back to My Memberships page.
		if (!$this->_getMembership()) {
			$app = JFactory::getApplication();
			$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));
		}

		
		$this->getParentFolders();
		$this->getExtraParentFolders();
		
		// Let's see if the membership is active
		if ($this->_data->status > 0) {
			return;
		}
		
		// let's get the path
		$path = $jinput->get('path', '', 'string');
		if (!empty($path)) 
		{
			$path = explode("|", $path);
			// extract the parent folder's id
			$parent_id = (int) $path[0];

			if (empty($parent_id)) 
				$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));

			// extract the path within the parent
			$path = !empty($path[1]) ? $path[1] : '';

			// check where are we looking
			$from = $this->getFrom();
			if ( $from == 'membership' ) 
				$parent = $this->_parents[$parent_id];
			elseif ( $from == 'extra' ) 
				$parent = $this->_extra_parents[$parent_id];

			// check if the parent is within the allowed parents list
			if (empty($parent)) 
				$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));

			$this->_parent = $parent_id;

			// compute the full path: parent + path
			$path 	= realpath($parent.'/'.$path);
			$parent = realpath($parent);

			// check if we are trying to access a path that's not within the parent
			if (strpos($path, $parent) !== 0)
				$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));
			
			// let's see if we've requested a download
			$task = $jinput->get('task', '', 'cmd');

			if ($task == 'download')
			{
				// check if path exists and is a file
				if ( is_file($path) ) 
				{
					// check if we need to agree to terms first
					$query
						->select($db->qn('term_id'))
						->from($db->qn('#__rsmembership_files'))
						->where($db->qn('path').' = '.$db->q($path));
					$db->setQuery($query);
					$term_id = $db->loadResult();

					if ( !empty($term_id) ) 
					{
						$row = JTable::getInstance('Term','RSMembershipTable');
						$row->load($term_id);
						if (!$row->published)
							$term_id = 0;
					}

					$agree = $jinput->get('agree', '', 'string');
					if (!empty($term_id) && empty($agree))
					{
						$this->terms = $row->description;
					}
					else
					{
						@ob_end_clean();
						$filename = basename($path);
						header("Cache-Control: public, must-revalidate");
						header('Cache-Control: pre-check=0, post-check=0, max-age=0');
						header("Pragma: no-cache");
						header("Expires: 0"); 
						header("Content-Description: File Transfer");
						header("Expires: Sat, 01 Jan 2000 01:00:00 GMT");
						if (preg_match('#Opera#', $_SERVER['HTTP_USER_AGENT']))
							header("Content-Type: application/octetstream");
						else
							header("Content-Type: application/octet-stream");

						header("Content-Length: ".(string) filesize($path));
						header('Content-Disposition: attachment; filename="'.$filename.'"');
						header("Content-Transfer-Encoding: binary\n");
						@readfile($path);
						$row 			= JTable::getInstance('Log','RSMembershipTable');
						$row->date 		= JFactory::getDate()->toSql();
						$row->user_id 	= $this->user->id;
						$row->path 		= '[DWN] '.$path;
						$row->ip 		= RSMembershipHelper::getIP();
						$row->store();
						exit();
					}
				}
				else
					$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));
			}
			else 
			{
				// check if the path exists and is a folder
				if ( is_dir($path) ) 
				{
					$this->_folder = $path;
					if ( substr($this->_folder, -1) == '/' ) 
						$this->_folder = substr($this->_folder, 0, -1);
				}
				else 
					$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));
			}
		}
	}

	protected function setNiceName($path, &$element) {
		static $cache;
		if (!is_array($cache)) {
			$db 	= JFactory::getDbo();
			$query 	= $db->getQuery(true);
			
			$query->select('*')
			  ->from($db->qn('#__rsmembership_files'));
			$db->setQuery($query);
			$cache = $db->loadObjectList('path');
		}
		
		if (!empty($cache[$path])) {
			$found					= &$cache[$path];
			$element->name 			= $found->name;
			$element->description 	= $found->description;
			$element->thumb 		= $found->thumb;
			$element->thumb_w 		= $found->thumb_w;
		}
	}

	protected function _getMembership() {
		$id 	= $this->user->id;
		$cid 	= $this->getCid();
		$db 	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query
			->select('ms.*')
			->select($db->qn('m.name'))
			->select($db->qn('m.term_id'))
			->select($db->qn('m.no_renew'))
			->select($db->qn('m.use_renewal_price'))
			->select($db->qn('m.renewal_price'))
			->select($db->qn('m.price'))
			->from($db->qn('#__rsmembership_membership_subscribers', 'ms'))
			->join('left', $db->qn('#__rsmembership_memberships', 'm').' ON '.$db->qn('ms.membership_id').' = '.$db->qn('m.id'))
			->where($db->qn('ms.id').' = '.$db->q($cid))
			->where($db->qn('ms.user_id').' = '.$db->q($id))
			->where($db->qn('m.published').' = '.$db->q(1));
		$db->setQuery($query);
		$this->_data = $db->loadObject();
		
		if (!$this->_data) {
			return false;
		}
		
		// Filter values
		$this->_data->extras = explode(',', $this->_data->extras);
		$this->_data->extras = array_filter($this->_data->extras);
		$this->_data->extras = implode(',', $this->_data->extras);
		
		return true;
	}


	public function getMembershipSubscriber($action_type = 'addextra')
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$ids = $this->_getIds($action_type);

		$query
			->select($db->qn('membership_id'))
			->select($db->qn('status'))
			->select($db->qn('extras'))
			->select($db->qn('last_transaction_id'))
			->from($db->qn('#__rsmembership_membership_subscribers'))
			->where($db->qn('user_id') . ' = ' . $db->q($user->get('id')))
			->where($db->qn('id') . ' = ' . $db->q($ids->cid));
		$db->setQuery($query);
		$membership = $db->loadObject();

		if (empty($membership)) {
			$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));
		}

		if ($check_addextra_upgrade = ($membership->status > 0 && ($action_type == 'addextra' || $action_type == 'upgrade')) || $check_renew = ($membership->status == 1 && $action_type == 'renew'))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_NOT_'.(isset($check_renew) ? 'EXPIRED' : 'ACTIVE')), 'warning');
			$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));
		}

		if ($action_type == 'upgrade') {
			$query->clear();
			$query
				->select('u.*')
				->select($db->qn('mfrom.name', 'fromname'))
				->select($db->qn('mto.name', 'toname'))
				->select($db->qn('mto.term_id'))
				->from($db->qn('#__rsmembership_membership_upgrades', 'u'))
				->join('left', $db->qn('#__rsmembership_memberships', 'mfrom').' ON '.$db->qn('mfrom.id').' = '.$db->qn('u.membership_from_id'))
				->join('left', $db->qn('#__rsmembership_memberships', 'mto').' ON '.$db->qn('mto.id').' = '.$db->qn('u.membership_to_id'))
				->where($db->qn('u.membership_from_id').' = '.$db->q($membership->membership_id))
				->where($db->qn('u.membership_to_id').' = '.$db->q($ids->to_id))
				->where($db->qn('u.published').' = '.$db->q(1));
			$db->setQuery($query);
			$return = $db->loadObject();

			if ( empty($return) )
			{
				$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));
			} else {
				$this->_data->term_id = $return->term_id;
			}

			return $return;
		}

		$last_transaction_id = $membership->last_transaction_id;

		$query->clear();
		$query
			->select('*')
			->from($db->qn('#__rsmembership_memberships'))
			->where($db->qn('id').' = '.$db->q($membership->membership_id));

		if ($action_type == 'addextra') {
			$query->where($db->qn('published').' = '.$db->q(1));
		}

		$db->setQuery($query);
		$membership = $db->loadObject();

		if ($action_type == 'renew') {
			if ( $membership->use_renewal_price )
				$membership->price = $membership->renewal_price;

			if ( $membership->no_renew )
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_CANNOT_RENEW'), 'warning');

				$app->redirect(JRoute::_(RSMembershipRoute::MyMemberships(), false));
			}
		}

		$membership->last_transaction_id = $last_transaction_id ;
		return $membership;
	}

	public function getSubscriberData()
	{
		$user 	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$query
			->select('*')
			->from($db->qn('#__rsmembership_subscribers'))
			->where($db->qn('user_id').' = '.$db->q($user->get('id')));
		$db->setQuery($query);

		return $db->loadObject();
	}


	public function getBoughtExtras()
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$return = array();

		if (!empty($this->_data->extras))
		{
			$query
				->select($db->qn('id'))
				->select($db->qn('extra_id'))
				->select($db->qn('name'))
				->from($db->qn('#__rsmembership_extra_values'))
				->where($db->qn('id').' IN ('.RSMembershipHelper::quoteImplode($this->_data->extras).')')
				->where($db->qn('published') .' = '. $db->q('1'))
				->order($db->qn('extra_id').' ASC, '.$db->qn('ordering').' ASC');
			$db->setQuery($query);
			$extravalues = $db->loadObjectList();

			foreach ( $extravalues as $extravalue ) 
				$return[$extravalue->extra_id][$extravalue->id] = $extravalue->name;
		}

		return $return;
	}

	public function getExtras($extras_values = false)
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$return = array();

		$query
			->select('e.*')
			->from($db->qn('#__rsmembership_membership_extras', 'me'))
			->join('left', $db->qn('#__rsmembership_extras', 'e').' ON '.$db->qn('me.extra_id') .' = '.$db->qn('e.id'))
			->where($db->qn('me.membership_id').' = '.$db->q($this->_data->membership_id))
			->where($db->qn('e.published').' = '.$db->q('1'));

		$db->setQuery($query);
		$extras = $db->loadObjectList();

		foreach ( $extras as $extra )
		{
			$query->clear();
			$query
				->select('*')
				->from($db->qn('#__rsmembership_extra_values'))
				->where($db->qn('extra_id').' = '.$db->q($extra->id))
				->where($db->qn('published').' = '.$db->q('1'))
				->order($db->qn('ordering').' ASC');

			if ( !$extras_values  && !empty($this->_data->extras) ) {
				$query->where($db->qn('id') . ' NOT IN (' . RSMembershipHelper::quoteImplode($this->_data->extras) . ')');
			} else if ($extras_values && !empty($extras_values)) {
				// condition used for the renew process
				$query->where($db->qn('id') . ' IN (' . RSMembershipHelper::quoteImplode($extras_values) . ')');
			}

			$db->setQuery($query);
			$values = $db->loadObjectList();

			if ( !empty($values) ) {
				foreach ($values as $value) {
					$value->type = $extra->type;
				}
			}
			$return = array_merge($return, $values);
		}

		return $return;
	}

	public function getBoughtExtrasRenew() {
		$extras_purchased = $this->getBoughtExtras();

		if (!empty($extras_purchased)) {
			$extras_values = array();
			foreach ($extras_purchased as $extras_p) {
				$extras_p = array_keys($extras_p);
				foreach ($extras_p as $extra_value_id) {

					$extras_values[] = $extra_value_id;
				}
			}

			return $this->getExtras($extras_values);
		}

		return array();
	}

	public function getSelectedExtra($extra_id)
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$extra_value_id = (int) $extra_id;
		$extra_value 	= JTable::getInstance('ExtraValue','RSMembershipTable');
		$extra_value->load($extra_value_id);

		$query
			->select('type')
			->from($db->qn('#__rsmembership_extras'))
			->where($db->qn('published').' = '.$db->q(1))
			->where($db->qn('id').' = '.$db->q($extra_value->extra_id));
		$db->setQuery($query);
		$extra_value->type = $db->loadResult();

		return $extra_value;
	}

	public function getUpgrades()
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$query
			->select('u.*')
			->select($db->qn('m.name'))
			->from($db->qn('#__rsmembership_membership_upgrades', 'u'))
			->join('left', $db->qn('#__rsmembership_memberships', 'm').' ON '.$db->qn('u.membership_to_id').' = '.$db->qn('m.id'))
			->where($db->qn('u.membership_from_id').' = '.$db->q($this->_data->membership_id))
			->where($db->qn('m.published').' = '.$db->q('1'))
			->where($db->qn('u.published').' = '.$db->q('1'));
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function getTerms()
	{
		return $this->terms;
	}

	public function getMembership()
	{
		return $this->_data;
	}

	public function getMembershipTerms()
	{
		if (!empty($this->_data->term_id))
		{
			$row = JTable::getInstance('Term','RSMembershipTable');
			$row->load($this->_data->term_id);
			if (!$row->published)
				return false;

			return $row;
		}

		return false;
	}

	public function getCid()
	{
		$input = JFactory::getApplication()->input;
		$payment = $input->getCmd('payment', '');
		if (!empty($payment) && $input->getCmd('layout', '') == 'payment') {
			$action_type = $input->getCmd('action_type', '');
			$ids = $this->_getIds($action_type);
			$cid = $ids->cid;
		} else {
			$cid = JFactory::getApplication()->input->get('cid', 0, 'int');
		}

		return $cid;
	}

	public function getExtraId(){
		return JFactory::getApplication()->input->get('extra_id', 0, 'int');
	}

	public function getToId()
	{
		return JFactory::getApplication()->input->get('to_id', 0, 'int');
	}

	public function getFrom()
	{
		return JFactory::getApplication()->input->get('from', 'membership', 'word');
	}

	public function getParentFolders()
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		// let's see if the membership is active
		if ($this->_data->status > 0)
			return $this->_parents;

		$query
			->select($db->qn('id'))
			->select($db->qn('params', 'path'))
			->from($db->qn('#__rsmembership_membership_shared'))
			->where($db->qn('membership_id').' = '.$db->q($this->_data->membership_id))
			->where($db->qn('type').' = '.$db->q('folder'))
			->where($db->qn('published').' = '.$db->q('1'))
			->order($db->qn('ordering').' ASC');
		$db->setQuery($query);

		$parents = $db->loadObjectList();
		foreach ($parents as $parent) {
			$this->_parents[$parent->id] = $this->cleanPath($parent->path);
		}
		
		return $this->_parents;
	}

	public function getExtraParentFolders()
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		// let's see if the membership is active
		if ($this->_data->status > 0)
			return $this->_extra_parents;

		if (empty($this->_data->extras)) 
			return $this->_extra_parents;

		$query
			->select($db->qn('id'))
			->select($db->qn('params'))
			->from($db->qn('#__rsmembership_extra_value_shared'))
			->where($db->qn('extra_value_id').' IN ('.RSMembershipHelper::quoteImplode($this->_data->extras).')')
			->where($db->qn('type').' = '.$db->q('folder'))
			->where($db->qn('published').' = '.$db->q('1'))
			->order($db->qn('ordering').' ASC');
		$db->setQuery($query);

		$parents = $db->loadObjectList();

		foreach ( $parents as $parent ) 
			$this->_extra_parents[$parent->id] = $this->cleanPath($parent->params);

		return $this->_extra_parents;
	}

	protected function cleanPath($path) {
		$path = realpath($path);
		$path = rtrim($path, '\\/');
		
		if ($this->isWindows) {
			$path = str_replace('\\', '/', $path);
		}
		
		return $path;
	}
	
	public function getFolders()
	{
		$folders 		= array();
		$all_folders 	= array();
		
		// let's see if the membership is active
		if ($this->_data->status > 0)
			return $folders;
		
		// Check if we are not browsing a folder
		if (is_null($this->_folder)) {
			// Show all the folders associated with this membership
			foreach ($this->_parents as $folder) {
				$all_folders[] = (object) array(
					'name' => $folder,
					'from' => 'membership'
				);
			}
			
			// Show all the folders associated with the extra values of this membership
			foreach ($this->_extra_parents as $folder) {
				$all_folders[] = (object) array(
					'name' => $folder,
					'from' => 'extra'
				);
			}
				
			// We don't need a parent since we have the full path in the database
			$parent = '';
		} else {
			// Show the folders in the current folder
			$subfolders = JFolder::folders($this->_folder);
			$from		= $this->getFrom();
			foreach ($subfolders as $folder) {
				$all_folders[] = (object) array(
					'name' => $folder,
					'from' => $from
				);
			}
			
			// We need the parent to be set as the current folder
			$parent = $this->_folder.'/';
		}
		
		// prepare our folders
		foreach ($all_folders as $folder) {
			// Membership or extra ?
			$from 	= $folder->from;
			// Get the folder's name
			$folder = $parent.$folder->name;
			// Clean it
			$folder = $this->cleanPath($folder);
			// Set folder name as default
			$name = strrchr($folder, '/');
			if ($name) {
				$name = ltrim($name, '/');
			} else {
				$name = $folder;
			}
			
			$element = (object) array(
				'from' => $from,
				'name' => $name,
				'description',
				'thumb',
				'thumb_w',
				'fullpath'
			);
			
			// Try to find the element name from the db
			// It's a folder so we need to append a slash
			$this->setNiceName($folder.'/', $element);
			
			// Select the array, defaults to memberships.
			$parents = $from == 'extra' ? $this->_extra_parents : $this->_parents;

			// Let's see if we are browsing the parent
			$pos = array_search($folder, $parents);
			if ($pos !== false) {
				// We are listing the available shared folders so we need the id of the parent as the path
				$element->fullpath = $pos;
			} else {
				// We are browsing through the parent so we need the subpath along with the id of the parent
				$element->fullpath = $this->_parent.'|'.substr_replace($folder, '', 0, strlen($parents[$this->_parent]) + 1);
			}
			
			$folders[] = $element;
		}
		
		return $folders;
	}
	
	public function getFiles()
	{
		$files = array();
		
		// let's see if the membership is active
		if ($this->_data->status > 0) 
			return $files;

		if (!is_null($this->_folder)) {
			$all_files = JFolder::files($this->_folder);
			$folder	   = $this->cleanPath($this->_folder);
			$from	   = $this->getFrom();
			
			foreach ($all_files as $file) {
				$element = (object) array(
					'from' => $from,
					'name' => $file,
					'description',
					'thumb',
					'thumb_w',
					'fullpath',
					'published' => 1
				);
				
				// Try to find the element name from the db
				$this->setNiceName($folder.'/'.$file, $element);
				
				// Select the array, defaults to memberships.
				$parents = $from == 'extra' ? $this->_extra_parents : $this->_parents;

				$element->fullpath = $this->_parent.'|'.substr_replace($folder.'/'.$file, '', 0, strlen($parents[$this->_parent]) + 1);
				
				$files[] = $element;
			}
		}
		
		return $files;
	}
	
	public function getCurrent() {
		return $this->_folder;
	}

	public function getPrevious() 
	{
		$from 		= $this->getFrom();
		$parents 	= $from == 'extra' ? $this->_extra_parents : $this->_parents;
		
		if (in_array($this->cleanPath($this->_folder), $parents)) { 
			return '';
		}

		if (!empty($this->_parent)) {
			$parts = explode('/', $this->cleanPath($this->_folder));
			if (count($parts) > 1) {
				array_pop($parts);
			}
			
			$folder = implode('/', $parts);
			$folder = substr_replace($folder, '', 0, strlen($parents[$this->_parent]) + 1);

			return $this->_parent.'|'.$folder;
		}

		return false;
	}

	public function cancel() {
		$db 	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$id  	= $this->user->id;
		$cid 	= $this->getCid();

		$transaction = JTable::getInstance('Transaction', 'RSMembershiptable');
		$transaction->load($this->_data->from_transaction_id);
		
		$membership  = JTable::getInstance('Membership', 'RSMembershiptable');
		$membership->load($this->_data->membership_id);

		$plugins = RSMembership::getPlugins();
		
		// Keep a legacy mode for Authorize.net
		if (in_array($transaction->gateway, $plugins) || $transaction->gateway == 'Authorize.Net') {
			$plugin = array_search($transaction->gateway, $plugins);
			if ($plugin === false) {
				$plugin = 'rsmembershipauthorize';
			}
			
			$args = array(
				'plugin' 		=> $plugin,
				'data' 			=> &$this->_data,
				'membership' 	=> $membership,
				'transaction' 	=> &$transaction
			);
			JFactory::getApplication()->triggerEvent('onMembershipCancelPayment', $args);
		}

		$query->clear();
		$query
			->update($db->qn('#__rsmembership_membership_subscribers'))
			->set($db->qn('status').' = '.$db->q('3'))
			->where($db->qn('id').' = '.$db->q($cid));
		$db->setQuery($query);
		$db->execute();

		if (!is_array($membership->gid_expire)) 
			$membership->gid_expire = explode(',', $membership->gid_expire);

		if ( $membership->gid_enable ) {
			RSMembership::updateGid($id, $membership->gid_expire, false, 'remove');
		}

		if ($membership->disable_expired_account)
		{
			list($memberships, $extras) = RSMembershipHelper::getUserSubscriptions($id);
				if (!$memberships) {
					RSMembership::disableUser($id);
					$app = JFactory::getApplication();
					$app->logout();
				}
		}
	}

	public function getExtra()
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$ids = $this->_getIds();

		$extra_value 	= JTable::getInstance('ExtraValue','RSMembershipTable');
		$extra_value->load($ids->extra_id);

		$query
			->select('type')
			->from($db->qn('#__rsmembership_extras'))
			->where($db->qn('published').' = '.$db->q(1))
			->where($db->qn('id').' = '.$db->q($extra_value->extra_id));
		$db->setQuery($query);
		$extra_value->type = $db->loadResult();

		return $extra_value;
	}

	public function checkBoughtExtra() {
		$ids = $this->_getIds();

		$membership_id 	= $ids->cid;
		$bought_extras 	= $this->getBoughtExtras();
		$current_extra 	= $this->getSelectedExtra($ids->extra_id);

		$app = JFactory::getApplication();

		// check if extra is already purchased
		if (empty($current_extra) || ( $current_extra->type != 'checkbox' && isset($bought_extras[$current_extra->extra_id]) ) ) {
			$app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_ALREADY_BOUGHT_EXTRA'), 'warning');
			// if the error is displayed we need to empty the session
			$this->_emptySession();

			$app->redirect(JRoute::_(RSMembershipRoute::MyMembership($membership_id), false));
		}
	}

	public function getHtml()
	{
		return $this->_html;
	}

	public function bindId($cid, $extra_id = null, $type = 'addextra')
	{
		$session = JFactory::getSession();
		$session->set($this->context.'.'.$type.'.cid', $cid);
		if ($extra_id != null && $type == 'addextra') {
			$session->set($this->context . '.' . $type . '.extra_id', $extra_id);
		} else if ($extra_id != null && $type == 'upgrade') {
			$session->set($this->context . '.' . $type . '.to_id', $extra_id);
		}
	}

	protected function _getIds($type = 'addextra')
	{
		static $ids;
		if (is_null($ids))
		{
			$session = JFactory::getSession();
			$ids = array();
			$ids['cid'] = (int)$session->get($this->context . '.'.$type.'.cid', 0);

			if ($type == 'addextra') {
				$ids['extra_id'] = (int)$session->get($this->context . '.'.$type.'.extra_id', 0);
			}

			if ($type == 'upgrade') {
				$ids['to_id'] = (int)$session->get($this->context . '.'.$type.'.to_id', 0);
			}

			$ids = (object) $ids;
		}

		return $ids;
	}

	protected function _emptySession($type = 'addextra')
	{
		$session = JFactory::getSession();
		if ($type == 'addextra') {
			$session->set($this->context . '.addextra.extra_id', null);
		}
		if ($type == 'upgrade') {
			$session->set($this->context . '.upgrade.to_id', null);
		}

		$session->set($this->context . '.'.$type.'.cid', null);
	}

	// renew/upgrade Specific
	public function storeData($params) {
		$session = JFactory::getSession();
		if (isset($params['id'])) {
			$context = $this->context.'.upgrade.';
			$session->set($context.'id', $params['id']);

			$newcontext = $context.$params['id'].'.';
			$session->set($newcontext.'membership_fields', $params['membership_fields']);
			$session->set($context.'custom_fields', $params['custom_fields']);
		} else {
			$session->set($this->context . '.renew.membership_fields', $params['membership_fields']);
			$session->set($this->context . '.renew.custom_fields', $params['custom_fields']);
		}
	}

	public function getSentData($action_type = 'renew') {
		$session = JFactory::getSession();
		$params	 = array();

		if ($action_type == 'upgrade') {
			$params['to_id'] = 0;

			$context = $this->context.'.upgrade.';
			if ($id = $session->get($context.'id')) {
				$params['to_id'] = $id;
			}
			if ($params['to_id']) {
				$newcontext = $context.$params['to_id'].'.';

				if ($membership_fields = $session->get($newcontext.'membership_fields')) {
					$params['membership_fields'] = $membership_fields;
				}
			}
			if ($custom_fields = $session->get($context.'custom_fields')) {
				$params['custom_fields'] = $custom_fields;
			}

		} else {
			if ($membership_fields = $session->get($this->context . '.renew.membership_fields')) {
				$params['membership_fields'] = $membership_fields;
			}
			if ($custom_fields = $session->get($this->context . '.renew.custom_fields')) {
				$params['custom_fields'] = $custom_fields;
			}
		}
		return $params;
	}

	public function addExtraPayment() {
		$ids = $this->_getIds();

		$this->_emptySession();

		$membership 	= $this->getMembershipSubscriber();
		$extra 			= $this->getExtra();
		$app = JFactory::getApplication();
		$paymentplugin 	= $app->input->get('payment', 'none', 'cmd');

		// check if extra is already purchased (redirects if it is) - we need to check it here two because it can be accessed by link directly
		$this->checkBoughtExtra();

		// calculate the total price
		$total = $extra->price;

		$user 		= JFactory::getUser();
		$user_id 	= $user->get('id');

		$row 			 = JTable::getInstance('Transaction','RSMembershipTable');
		$row->user_id 	 = $user_id;
		$row->user_email = $user->get('email');

		$this->_data = new stdClass();
		$this->_data->username 	= $user->get('username');
		$this->_data->name 		= $user->get('name');
		$this->_data->email 	= $user->get('email');
		$this->_data->fields 	= RSMembershipHelper::getUserFields($user->get('id'));
		$membership_fields 				= RSMembershipHelper::getTransactionMembershipFields($user->get('id'), $membership->last_transaction_id);
		if (count($membership_fields)) {
			$this->_data->membership_fields = $membership_fields;
		}

		$row->user_data = serialize($this->_data);

		$row->type = 'addextra';
		$params = array();
		$params[] = 'id='.$ids->cid;
		$params[] = 'membership_id='.$membership->id;
		$params[] = 'extras='.$extra->id;


		$transaction_data = array(
			'extras' => array()
		);

		$transaction_data['extras'][] = (object) array('name' => $extra->name, 'price' => $extra->price);
		$transaction_data = json_encode($transaction_data);


		$row->params 			= implode(';', $params); // params, membership, extras etc
		$row->date 				= JFactory::getDate()->toSql();
		$row->ip 				= RSMembershipHelper::getIP();
		$row->price 			= $total;
		$row->currency 			= RSMembershipHelper::getConfig('currency');
		$row->hash 				= '';
		$row->gateway 			= $paymentplugin == 'none' ? 'No Gateway' : RSMembership::getPlugin($paymentplugin);
		$row->status 			= 'pending';
		$row->transaction_data 	= $transaction_data;

		$this->_html = '';

		// trigger the payment plugin
		$delay = false;
		$args  = array(
			'plugin' => $paymentplugin,
			'data' => &$this->_data,
			'extras' => array(),
			'membership' => $membership,
			'transaction' => &$row,
			'html' => &$this->_html
		);

		$returns = $app->triggerEvent('onMembershipPayment', $args);

		// PHP 5.4 fix...
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			foreach ($returns as $value) {
				if ($value) {
					$this->_html = $value;
				}
			}
		}

		$properties = $row->getProperties();
		$returns = $app->triggerEvent('delayTransactionStoring', array(array('plugin' => $paymentplugin, 'properties' => &$properties, 'delay' => &$delay)));

		// PHP 5.4 fix...
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			foreach ($returns as $value) {
				if ($value) {
					$delay = true;
				}
			}
		}

		// plugin can delay the transaction storing
		if ( !$delay )
		{
			// store the transaction
			$row->store();

			// store the transaction id
			$this->transaction_id = $row->id;

			// finalize the transaction (send emails)
			RSMembership::finalize($this->transaction_id);

			// approve the transaction
			if ( $row->status == 'completed' || ($row->price == 0 && $membership->activation != 0) )
				RSMembership::approve($this->transaction_id, true);

			if ( $row->price == 0 )
				$app->redirect(JRoute::_(RSMembershipRoute::ThankYou(), false));
		}
	}

	public function renewPayment() {
		$ids = $this->_getIds('renew');

		$this->_emptySession('renew');

		$membership 	= $this->getMembershipSubscriber('renew');
		$extras 		= $this->getBoughtExtrasRenew();
		$app = JFactory::getApplication();
		$paymentplugin 	= $app->input->get('payment', 'none', 'cmd');

		// calculate the total price
		$total 		= 0;
		$total 	   += $membership->price;
		$extras_ids = array();
		foreach ( $extras as $extra ) {
			$total 		 += $extra->price;
			$extras_ids[] = $extra->id;
		}

		$user 		= JFactory::getUser();
		$user_id 	= $user->get('id');

		$row 			 = JTable::getInstance('Transaction','RSMembershipTable');
		$row->user_id 	 = $user_id;
		$row->user_email = $user->get('email');

		$this->_data = new stdClass();
		$this->_data->username 			= $user->get('username');
		$this->_data->name 				= $user->get('name');
		$this->_data->email 			= $user->get('email');

		$membership_data =  $this->getSentData();

		if (isset($membership_data['custom_fields'])) {
			$this->_data->fields = $membership_data['custom_fields'];
		}

		if (isset($membership_data['membership_fields'])) {
			$this->_data->membership_fields = $membership_data['membership_fields'];
		}
		$row->user_data = serialize($this->_data);

		$row->type 	= 'renew';
		$params 	= array();
		$params[] 	= 'id='.$ids->cid;
		$params[] 	= 'membership_id='.$membership->id;
		if ( !empty($extras_ids) ) {
			$params[] = 'extras=' . implode(',', $extras_ids);
		}

		$row->params 	= implode(';', $params); // params, membership, extras etc
		$row->date 		= JFactory::getDate()->toSql();
		$row->ip 		= RSMembershipHelper::getIP();
		$row->price 	= $total;
		$row->currency 	= RSMembershipHelper::getConfig('currency');
		$row->hash 		= '';
		$row->gateway 	= $paymentplugin == 'none' ? 'No Gateway' : RSMembership::getPlugin($paymentplugin);
		$row->status 	= 'pending';

		$this->_html = '';

		// trigger the payment plugin
		$delay = false;
		$args  = array(
			'plugin' => $paymentplugin,
			'data' => &$this->_data,
			'extras' => $extras,
			'membership' => $membership,
			'transaction' => &$row,
			'html' => &$this->_html
		);

		// trigger the payment plugin
		$returns = $app->triggerEvent('onMembershipPayment', $args);

		// PHP 5.4 fix...
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			foreach ($returns as $value) {
				if ($value) {
					$this->_html = $value;
				}
			}
		}

		$properties = $row->getProperties();
		$returns = $app->triggerEvent('delayTransactionStoring', array(array('plugin' => $paymentplugin, 'properties' => &$properties, 'delay' => &$delay)));

		// PHP 5.4 fix...
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			foreach ($returns as $value) {
				if ($value) {
					$delay = true;
				}
			}
		}

		// plugin can delay the transaction storing
		if ( !$delay )
		{
			// store the transaction
			$row->store();

			// store the transaction id
			$this->transaction_id = $row->id;

			// finalize the transaction (send emails)
			RSMembership::finalize($this->transaction_id);

			// approve the transaction
			if ( $row->status == 'completed' || ($row->price == 0 && $membership->activation != 0) )
				RSMembership::approve($this->transaction_id, true);

			if ( $row->price == 0 )
				$app->redirect(JRoute::_('index.php?option=com_rsmembership&task=thankyou', false));
		}
	}

	public function upgradePayment() {
		$ids = $this->_getIds('upgrade');

		$this->_emptySession('upgrade');

		$extras 		= array();
		$upgrade 		= $this->getMembershipSubscriber('upgrade');
		$membership 	= $this->getMembershipUpgrade($upgrade->membership_to_id);

		$app = JFactory::getApplication();
		$paymentplugin 	= $app->input->get('payment', 'none', 'cmd');

		// calculate the total price
		$total = $upgrade->price;

		$user 	 = JFactory::getUser();
		$user_id = $user->get('id');

		$row = JTable::getInstance('Transaction','RSMembershipTable');
		$row->user_id = $user_id;
		$row->user_email = $user->get('email');

		$this->_data = new stdClass();
		$this->_data->username 	= $user->get('username');
		$this->_data->name 		= $user->get('name');
		$this->_data->email 	= $user->get('email');

		$membership_data=  $this->getSentData('upgrade');
		if (isset($membership_data['custom_fields'])) {
			$this->_data->fields = $membership_data['custom_fields'];
		}
		if ($membership_data['to_id'] == $upgrade->membership_to_id ) {
			if (isset($membership_data['membership_fields'])) {
				$this->_data->membership_fields = $membership_data['membership_fields'];
			}
		}
		$row->user_data 		= serialize($this->_data);

		$row->type = 'upgrade';
		$params = array();
		$params[] = 'id='.$ids->cid;
		$params[] = 'from_id='.$upgrade->membership_from_id;
		$params[] = 'to_id='.$upgrade->membership_to_id;

		$row->params 	= implode(';', $params); // params, membership, extras etc
		$row->date 		= JFactory::getDate()->toSql();
		$row->ip 		= RSMembershipHelper::getIP();
		$row->price 	= $total;
		$row->currency 	= RSMembershipHelper::getConfig('currency');
		$row->hash 		= '';
		$row->gateway 	= $paymentplugin == 'none' ? 'No Gateway' : RSMembership::getPlugin($paymentplugin);
		$row->status 	= 'pending';

		$this->_html = '';

		// trigger the payment plugin
		$delay = false;
		$args  = array(
			'plugin' => $paymentplugin,
			'data' => &$this->_data,
			'extras' => $extras,
			'membership' => $membership,
			'transaction' => &$row,
			'html' => &$this->_html
		);

		$returns = $app->triggerEvent('onMembershipPayment', $args);

		// PHP 5.4 fix...
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			foreach ($returns as $value) {
				if ($value) {
					$this->_html = $value;
				}
			}
		}

		$properties = $row->getProperties();
		$returns = $app->triggerEvent('delayTransactionStoring', array(array('plugin' => $paymentplugin, 'properties' => &$properties, 'delay' => &$delay)));

		// PHP 5.4 fix...
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			foreach ($returns as $value) {
				if ($value) {
					$delay = true;
				}
			}
		}

		// trigger the payment plugin
		// plugin can delay the transaction storing
		if (!$delay)
		{
			// store the transaction
			$row->store();

			// store the transaction id
			$this->transaction_id = $row->id;

			// finalize the transaction (send emails)
			RSMembership::finalize($this->transaction_id);

			// approve the transaction
			if ( $row->status == 'completed' || ($row->price == 0 && $membership->activation != 0) )
				RSMembership::approve($this->transaction_id, true);

			if ( $row->price == 0 )
				$app->redirect(JRoute::_('index.php?option=com_rsmembership&task=thankyou', false));
		}
	}

	public function getMembershipUpgrade ($cid)
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$cid	= (int) $cid;

		$query
			->select('*')
			->from($db->qn('#__rsmembership_memberships'))
			->where($db->qn('published').' = '.$db->q(1))
			->where($db->qn('id').' = '.$db->q($cid));
		$db->setQuery($query);

		return $db->loadObject();
	}
}