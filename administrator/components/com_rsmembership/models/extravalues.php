<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipModelExtraValues extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) 
			$config['filter_fields'] = array('id', 'name', 'price', 'published', 'ordering');

		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);

		$query->select('*')->from($db->qn('#__rsmembership_extra_values'));

		// search filter
		$filter_word = $this->getState($this->context.'.filter.search');
		if (strlen($filter_word)) {
			$query->where($db->qn('name').' LIKE '.$db->q('%'.$filter_word.'%'));
			$query->where($db->qn('description').' LIKE '.$db->q('%'.$filter_word.'%'));
		}

		// state filter
		$filter_state = $this->getState($this->context.'.filter.filter_state');
		if (is_numeric($filter_state)) 
			$query->where($db->qn('published').' = '.$db->q($filter_state));

		$extra_id	= $this->getState($this->context.'.filter.extra_id');
		if ($extra_id) 
			$query->where($db->qn('extra_id') .' = '. $db->q($extra_id));

		$listOrdering  	= $this->getState('list.ordering', 'ordering');
		$listDirection 	= $this->getState('list.direction', 'ASC');

		$query->order($db->qn($listOrdering).' '.$listDirection);

		return $query;
	}

	public function getTable($type = 'ExtraValue', $prefix = 'RSMembershipTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function populateState($ordering = null, $direction = null) 
	{
		$app = JFactory::getApplication();

		$this->setState($this->context.'.filter.search', 		$app->getUserStateFromRequest($this->context.'.extravalues.search', 'filter_search'));
		$this->setState($this->context.'.filter.filter_state', 	$app->getUserStateFromRequest($this->context.'.extravalues.filter_state', 'filter_state'));
		$this->setState($this->context.'.filter.extra_id', 	 	$app->getUserStateFromRequest($this->context.'.extravalues.extra_id', 'extra_id'));

		parent::populateState('ordering', 'ASC');
	}

	public function getOrdering() {
		require_once JPATH_COMPONENT.'/helpers/adapters/ordering.php';

		$ordering = new RSOrdering();
		return $ordering;
	}

	public function getFilterBar() 
	{
		require_once JPATH_COMPONENT.'/helpers/adapters/filterbar.php';

		// Search filter
		$options['search'] = array(
			'label' => JText::_('JSEARCH_FILTER'),
			'value' => $this->getState($this->context.'.filter.search')
		);
		
		// Ordering results
		$options['listOrder']  = $this->getState('list.ordering', 'ordering');
		$options['listDirn']   = $this->getState('list.direction', 'ASC');

		$options['sortFields'] = array(
			JHtml::_('select.option', 'id', 		JText::_('JGRID_HEADING_ID')),
			JHtml::_('select.option', 'name', 		JText::_('COM_RSMEMBERSHIP_EXTRA_VALUE')),
			JHtml::_('select.option', 'price', 		JText::_('COM_RSMEMBERSHIP_EXTRA_VALUE_PRICE')),
			JHtml::_('select.option', 'published', 	JText::_('JPUBLISHED')),
			JHtml::_('select.option', 'ordering', 	JText::_('JGRID_HEADING_ORDERING'))
		);

		// Extravalues States filter
		$options['states'] = array(
			JHtml::_('select.option', '', JText::_('COM_RSMEMBERSHIP_MEMBERSHIPS_FILTER_BY_STATE')),
			JHtml::_('select.option', '1', JText::_('COM_RSMEMBERSHIP_MEMBERSHIPS_FILTER_PUBLISHED')),
			JHtml::_('select.option', '0', JText::_('COM_RSMEMBERSHIP_MEMBERSHIPS_FILTER_UNPUBLISHED'))
		);
		$options['filter_state'] 	= $this->getState($this->context.'.filter.filter_state');

		// Joomla 2.5
		$options['rightItems'] = array(
			array(
				'input' => '<select name="filter_state" class="inputbox" onchange="this.form.submit()">'."\n"
						   .JHtml::_('select.options', $options['states'], 'value', 'text', $options['filter_state'], false)."\n"
						   .'</select>'
			)
		);

		$bar = new RSFilterBar($options);

		return $bar;
	}

	public function getSideBar() 
	{
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';

		return RSMembershipToolbarHelper::render();
	}
}