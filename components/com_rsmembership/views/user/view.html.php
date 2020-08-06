<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewUser extends JViewLegacy
{
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();

        // get parameters
        $this->params = clone($app->getParams('com_rsmembership'));
        $this->fields = RSMembershipHelper::getFields();
        $this->field  = $this->get('RSFieldset');
        $this->allow_self_anonymisation = RSMembershipHelper::getConfig('allow_self_anonymisation', 0) && !$user->authorise('core.admin');
        $this->email = $user->email;

        // Check for field validations
        $fields_validation 	= RSMembershipHelper::getFieldsValidation();
        if (!empty($fields_validation)) {
            JFactory::getDocument()->addScriptDeclaration('jQuery(function() {'."\n\t".'RSMembership.subscribe.init.field_validations = '.json_encode($fields_validation).';'."\n".'});'."\n");
        }

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $active = $app->getMenu()->getActive();
        if ($active
            && $active->component == 'com_rsmembership'
            && isset($active->query['view'])
            && $active->query['view'] == 'user')
        {
            $this->params->def('page_heading', $this->params->get('page_title', $active->title));
        }

        // Description
        if ($this->params->get('menu-meta_description'))
            $this->document->setDescription($this->params->get('menu-meta_description'));
        // Keywords
        if ($this->params->get('menu-meta_keywords'))
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        // Robots
        if ($this->params->get('robots'))
            $this->document->setMetadata('robots', $this->params->get('robots'));

        parent::display();
    }
}