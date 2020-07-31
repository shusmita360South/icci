<?php
# no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

class ServicesModelList extends JModelList {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        
        $app 	= JFactory::getApplication();
        $params = $app->getParams();
		
#		echo '<pre>';print_r($params);echo '</pre>';

        $this->keyword          = JRequest::getVar('keyword');
		$this->authorId       	= JRequest::getVar('author');
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
				
		$query->select('ni.*');



		$query->from($db->quoteName('#__services_items', 'ni'));
		$query->join('LEFT', $db->quoteName('#__services_categories', 'nc') . ' ON (' . $db->quoteName('ni.catid') . ' = ' . $db->quoteName('nc.id') . ')');
		$query->where('ni.state = 1');


		if($this->typeId) {
			$query->where('ni.catid = ' . $db->quote($this->typeId));
		}

		if($this->authorId) {
			$query->where('ni.author = ' . $db->quote($this->authorId));
		}

		if($this->sort) {
			$query->order('ni.' . $this->sort . ' ' . $this->order . ', ni.id ' . $this->order);
		} else {
			$query->order('ni.ordering DESC');
		}
		
        return $query;
    }

}
