<?php
# no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

class DirectoryModelList extends JModelList {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        
        $app 	= JFactory::getApplication();
        $params = $app->getParams();
		
#		echo '<pre>';print_r($params);echo '</pre>';

        $this->keyword          = JRequest::getVar('keyword');
        $this->searchalpha      = JRequest::getVar('searchalpha');
		$this->category       	= JRequest::getVar('category');
		$this->typeId           = JRequest::getVar('type');
		$this->sort             = JRequest::getVar('sort');
		$this->order            = JRequest::getVar('order');
		$this->Itemid           = JRequest::getVar('Itemid');
		
		$value = $params->get('display_num');
		//$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);		
		
        #parent::populateState($ordering, $direction);
    }

    protected function getListQuery() {

		$app 		= JFactory::getApplication();
		$menu 		= $app->getMenu();
		$active 	= $menu->getActive();
		$params 	= new JRegistry;
		
		$params->loadString($active->params);
				
		$db 		= $this->getDbo();
		$query 		= $db->getQuery(true);
				
		$query->select('s.*, u.*');



		$query->from($db->quoteName('#__rsmembership_subscribers', 's'));
		$query->join('LEFT', $db->quoteName('#__rsmembership_membership_subscribers', 'nc') . ' ON (' . $db->quoteName('s.user_id') . ' = ' . $db->quoteName('nc.user_id') . ')');
		$query->join('LEFT', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('s.user_id') . ' = ' . $db->quoteName('u.id') . ')');

		$query->where('nc.published = 1');


		if($this->typeId) {
			$query->where('s.catid = ' . $db->quote($this->typeId));
		}

		if($this->category) {
			$query->where('s.f20 = ' . $db->quote($this->category));
		}

		if($this->keyword) {
			$query->where('s.f8 LIKE "%' . $this->keyword . '%"');
		}

		if($this->searchalpha  && ($this->searchalpha!='0-9')) {
			$query->where('s.f8 LIKE "' . $this->searchalpha . '%"');
		}

		if($this->searchalpha && ($this->searchalpha=='0-9')) {
			$query->where('s.f8 REGEXP "^[0-9B]"');
		}

		/*if($this->sort) {
			$query->order('s.' . $this->sort . ' ' . $this->order . ', s.id ' . $this->order);
		} else {
			$query->order('s.ordering DESC');
		}*/
		
        return $query;
    }

}
