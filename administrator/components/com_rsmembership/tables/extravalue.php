<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipTableExtraValue extends JTable
{
	public function __construct(& $db) 
	{
		parent::__construct('#__rsmembership_extra_values', 'id', $db);
	}

	public function check()
	{
		$extra_id = JFactory::getApplication()->input->get('extra_id', 0, 'int');
		if ( $extra_id )
			$this->extra_id = $extra_id;
		
		if (!$this->id)
			$this->ordering = $this->getNextOrder();

		return true;
	}
	
	public function getParentName() 
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		
		$query->select($db->qn('name'))->from($db->qn('#__rsmembership_extras'))->where($db->qn('id').' = '.$db->q($this->extra_id));
		$db->setQuery($query);
		
		return $db->loadResult();
	}

}