<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsmembershipControllerMymembership extends JControllerForm
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }

    public function getModel($name = 'Mymembership', $prefix = 'RsmembershipModel', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, $config);
    }

    public function addExtraPaymentRedirect() {
        $payment 	= $this->input->get('payment', 'none', 'string');
        $cid        = $this->input->getInt('cid');
        $extra_id   = $this->input->getInt('extra_id');
        $model      = $this->getModel();
        $app        = JFactory::getApplication();

        // Check if terms and conditions have been accepted
        if ($model->getMembershipTerms() && !$this->input->getInt('i_agree_to_terms')) {
            // Show some errors.
            $app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_PLEASE_AGREE_MEMBERSHIP'), 'error');

            // Redirect back.
            return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=addextra&cid='.$cid.'&extra_id='.$extra_id, false));
        }

        $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=payment&payment='.$payment.'&action_type=addextra', false));
    }

    public function renewPaymentRedirect(){
        $app        = JFactory::getApplication();
        $jinput  	= $app->input;
        $payment 	= $jinput->get('payment', 'none', 'string');
        $cid 	 	= $jinput->get('cid', 0, 'int');
        $model 	 	= $this->getModel();

        $membership = $model->getMembershipSubscriber('renew');

        $all_fields 			= RSMembership::getCustomFields();
        $membership_fields 		= RSMembership::getCustomMembershipFields($membership->id);
        $all_fields 			= array_merge($all_fields, $membership_fields);
        // Check if terms and conditions have been accepted
        if ($model->getMembershipTerms() && !$jinput->getInt('i_agree_to_terms')) {
            // Show some errors.
            $app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_PLEASE_AGREE_MEMBERSHIP'), 'error');

            // Redirect back.
            return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=renew&cid='.$cid, false));
        }

        if (count($all_fields)) {
            $verifyFieldsMembership = $jinput->get('rsm_membership_fields', array(), 'array');
            $verifyFieldsUser 	 	= $jinput->get('rsm_fields', array(), 'array');
            $verifyFields			= array_merge($verifyFieldsUser, $verifyFieldsMembership);
            $fields  		 	    = $all_fields;
            foreach ($fields as $field) {
                if (($field->required && empty($verifyFields[$field->name])) ||
                    ($field->rule && !empty($verifyFields[$field->name]) && is_callable('RSMembershipValidation', $field->rule) && !call_user_func(array('RSMembershipValidation', $field->rule), $verifyFields[$field->name]))) {
                    $message = JText::_($field->validation);
                    if (empty($message)) {
                        $message = JText::sprintf('COM_RSMEMBERSHIP_VALIDATION_DEFAULT_ERROR', JText::_($field->label));
                    }

                    JFactory::getApplication()->enqueueMessage($message, 'warning');
                    return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=renew&cid='.$cid, false));
                }
            }

            $model->storeData(array(
                'membership_fields'	=> $verifyFieldsMembership,
                'custom_fields'		=> $verifyFieldsUser
            ));
        }

        $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=payment&payment='.$payment.'&action_type=renew', false));
    }

    public function upgrade()
    {
        $user 	= JFactory::getUser();
        $db 	= JFactory::getDBO();
        $query	= $db->getQuery(true);
        $jinput = JFactory::getApplication()->input;
        $to_id	= $jinput->get('to_id', 0, 'int');
        $cid	= $jinput->get('cid', 0, 'int');

        $query->select($db->qn('unique'))->from($db->qn('#__rsmembership_memberships'))->where($db->qn('id').' = '.$db->q($to_id));
        $db->setQuery($query);

        if ( $db->loadResult() > 0 )
        {
            $query->clear();
            $query->select($db->qn('id'))->from($db->qn('#__rsmembership_membership_subscribers'))->where($db->qn('user_id').' = '.$db->q($user->get('id')))->where( $db->qn('membership_id').' = '.$db->q($to_id) );
            $db->setQuery($query);

            if ( $db->loadResult() )
            {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_RSMEMBERSHIP_ALREADY_SUBSCRIBED'), 'warning');
                return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership', false));
            }
        }

        return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=upgrade&cid='.$cid.'&to_id='.$to_id, false));
    }

    public function upgradePaymentRedirect()
    {
        $app     = JFactory::getApplication();
        $jinput  = $app->input;
        $payment = $jinput->get('payment', 'none', 'string');
        $cid 	 = $jinput->get('cid', 0, 'int');
        $to_id 	 = $jinput->get('to_id', 0, 'int');
        $model 	 = $this->getModel();
        $upgrade = $model->getMembershipSubscriber('upgrade');

        $all_fields 			= RSMembership::getCustomFields();
        $membership_fields 		= RSMembership::getCustomMembershipFields($upgrade->membership_to_id);
        $all_fields 			= array_merge($all_fields, $membership_fields);

        // Check if terms and conditions have been accepted
        if ($model->getMembershipTerms() && !$jinput->getInt('i_agree_to_terms')) {
            // Show some errors.
            $app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_PLEASE_AGREE_MEMBERSHIP'), 'error');

            // Redirect back.
            return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=upgrade&cid='.$cid.'&to_id='.$to_id, false));
        }

        if (count($all_fields)) {
            $to_id 	 		 		= $jinput->get('to_id', 0, 'int');
            $verifyFieldsMembership = $jinput->get('rsm_membership_fields', array(), 'array');
            $verifyFieldsUser 	 	= $jinput->get('rsm_fields', array(), 'array');
            $verifyFields			= array_merge($verifyFieldsUser, $verifyFieldsMembership);

            $fields  = $all_fields;
            foreach ($fields as $field) {
                if (($field->required && empty($verifyFields[$field->name])) ||
                    ($field->rule && !empty($verifyFields[$field->name]) && is_callable('RSMembershipValidation', $field->rule) && !call_user_func(array('RSMembershipValidation', $field->rule), $verifyFields[$field->name]))) {
                    $message = JText::_($field->validation);
                    if (empty($message)) {
                        $message = JText::sprintf('COM_RSMEMBERSHIP_VALIDATION_DEFAULT_ERROR', JText::_($field->label));
                    }

                    JFactory::getApplication()->enqueueMessage($message, 'warning');
                    return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=upgrade&cid='.$cid.'&to_id='.$to_id, false));
                }
            }

            $model->storeData(array(
                'id' 		        => $to_id,
                'membership_fields'	=> $verifyFieldsMembership,
                'custom_fields'		=> $verifyFieldsUser
            ));
        }

        $this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=mymembership&layout=payment&payment='.$payment.'&action_type=upgrade', false));
    }
}