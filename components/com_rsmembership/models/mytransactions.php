<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelMytransactions extends JModelList
{
    public $_context = 'com_rsmembership.mytransactions';

    public function __construct($config = array())
    {
        parent::__construct($config);

        $user = JFactory::getUser();
        if ( $user->get('guest') )
        {
            $link 		 = JUri::getInstance();
            $link 		 = base64_encode($link);
            $user_option = 'com_users';

            JFactory::getApplication()->redirect('index.php?option='.$user_option.'&view=login&return='.$link);
        }
    }

    public function getTable($type = 'Transaction', $prefix = 'RSMembershipTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function getListQuery()
    {
        $user 	= JFactory::getUser();
        $db 	= JFactory::getDBO();
        $query	= $db->getQuery(true);

        $query
            ->select('*')
            ->from($db->qn('#__rsmembership_transactions'))
            ->where($db->qn('user_id').' = '.$db->q($user->get('id')))
            ->order($db->qn('date').' DESC');

        return $query;
    }

    public function getItems(){
        $items = parent::getItems();

        $cache_membership_data = array();

        foreach ($items as $item)
        {
            $membership_id_model = 0;

            $params = RSMembershipHelper::parseParams($item->params);
            switch($item->type)
            {
                case 'new':
                case 'renew':
                case 'addextra':
                    $membership_id_model = !empty($params['membership_id']) ? $params['membership_id'] : 0;
                    break;

                case 'upgrade':
                    $membership_id_model = !empty($params['to_id']) ? $params['to_id'] : 0;
                    break;
            }

            if (!empty($membership_id_model) && !isset($cache_membership_data[$membership_id_model]))
            {
                $cache_membership_data[$membership_id_model] = RSMembership::getMembershipData($membership_id_model);
            }

            if (!empty($membership_id_model))
            {
                $item->membership_data = $cache_membership_data[$membership_id_model];
            }
            else
            {
                $item->membership_data = false;
            }
        }

        return $items;
    }
}