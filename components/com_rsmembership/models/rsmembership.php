<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelRSMembership extends JModelList
{
	public $_context = 'rsmembership';

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'm.id', 'm.name', 'c.name', 'price'
            );
        }

        parent::__construct($config);
    }

	public function getTable($type = 'Membership', $prefix = 'RSMembershipTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function getListQuery() 
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$params = $this->getState($this->context.'.params');

		$query
			->select('COALESCE(`c`.`name`, '.$db->q('').') AS '.$db->qn('category_name').', m.*')
			->from($db->qn('#__rsmembership_memberships', 'm'))
			->join('left', $db->qn('#__rsmembership_categories', 'c').' ON '.$db->qn('c.id').' = '.$db->qn('m.category_id'))
			->where($db->qn('m.published').' = '.$db->q('1'));

		if ( $category = $this->getCategory() )
		{
			$query->where( $db->qn('m.category_id')." = ".$db->q($category->id) );
		}
		else 
		{
			$categories = $params->get('categories', array());
			if ( !is_array($categories) )
				$categories = (array) $categories;

			if ( !empty($categories) ) 
				$query->where($db->qn('m.category_id').'  IN ('.RSMembershipHelper::quoteImplode($categories).')');
		}

        $listOrdering = $this->getState('list.ordering', 'ordering');
		$listDirection 	= $this->getState('list.direction', 'ASC');

		$query->order($db->qn($listOrdering).' '.$listDirection);

		return $query;
	}

	protected function populateState($ordering = null, $direction = null) 
	{
		if ($active = JFactory::getApplication()->getMenu()->getActive())
        {
            $params = $active->params;
        }
        else
        {
            $params = new JRegistry;
            $params->set('orderby', 'ordering');
            $params->set('orderdir', 'ASC');
        }

        $this->setState($this->context.'.params', $params);

		parent::populateState($params->get('orderby', 'ordering'), $params->get('orderdir', 'ASC'));
	}

	public function getItems()
	{
		$items = parent::getItems();

		if (!empty($items))
		{
			$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';

			if (is_array($items))
			{
				foreach ( $items as $i => $row )
				{
					if ( $row->use_trial_period ) 
						$items[$i]->price = $row->trial_price;

					if ( preg_match($pattern, $row->description) )
						list($row->description, $fulldescription) = preg_split($pattern, $row->description, 2);
				}
			}
		}

		return $items;
	}

	public function getCategory()
    {
        $id = JFactory::getApplication()->input->get('catid', 0, 'int');
        $table = JTable::getInstance('Category', 'RSMembershipTable');
        if ($id)
        {
            $table->load($id);
            return $table;
        }

        return false;
    }
}