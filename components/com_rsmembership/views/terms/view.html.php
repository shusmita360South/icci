<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewTerms extends JViewLegacy
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		
		$terms 		= $this->get('terms');
		$pathway 	= $app->getPathway();
		$pathway->addItem($terms->name, '');
		
		// get parameters
		$this->params = clone($app->getParams('com_rsmembership'));
		
		$this->terms = $terms;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $active = $app->getMenu()->getActive();
        if ($active
            && $active->component == 'com_rsmembership'
            && isset($active->query['view'], $active->query['cid'])
            && $active->query['view'] == 'terms'
            && $active->query['cid'] == $this->terms->id)
        {
            $this->params->def('page_heading', $this->params->get('page_title', $active->title));
        }
        else
        {
            $this->params->def('page_heading', $this->terms->name);
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