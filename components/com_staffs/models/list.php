<?php
# no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

class StaffsModelList extends JModelList {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        
        $app 	= JFactory::getApplication();
        $params = $app->getParams();
		
#		echo '<pre>';print_r($params);echo '</pre>';

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
		$catid    = $params['catid'];
				
		$db 		= $this->getDbo();
		$query 		= $db->getQuery(true);
				
		$query->select('ni.*');



		$query->from($db->quoteName('#__staffs_items', 'ni'));
		$query->join('LEFT', $db->quoteName('#__staffs_categories', 'nc') . ' ON (' . $db->quoteName('ni.catid') . ' = ' . $db->quoteName('nc.id') . ')');
		$query->where('ni.state = 1');


		$query->where('ni.catid = ' . $db->quote($catid));

		$query->order('ni.ordering DESC');

		
        return $query;
    }

}
