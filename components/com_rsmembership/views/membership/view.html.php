<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewMembership extends JViewLegacy
{
	public function display($tpl = null)
	{
		$app 			= JFactory::getApplication();
		$params 		= clone($app->getParams('com_rsmembership'));
		$this->params 	= $params;
		$this->item 	= $this->get('Item');
		
		if ( empty($this->item->id) || !$this->item->published ) 
		{
			$app->enqueueMessage(JText::_('COM_RSMEMBERSHIP_MEMBERSHIP_NOT_EXIST'), 'warning');
			$app->redirect(JRoute::_(RSMembershipRoute::Memberships()));
		}

		$currency = RSMembershipHelper::getConfig('currency');
		// {price} placeholder
		$price 	  = RSMembershipHelper::getPriceFormat($this->item->price);
		
		// {extras} placeholder
		$viewclass = 'JViewLegacy';

		$view = new $viewclass(array(
			'name' 		=> 'extras',
			'base_path' => JPATH_SITE.'/components/com_rsmembership'
		));

		$view->model 			  = JModelLegacy::getInstance('Extras', 'RSMembershipModel', array('ignore_request' => true));
		$view->extras 			  = $view->model->getItems();
		$view->item 			  = $this->item;
		$view->show_subscribe_btn = ( $this->item->stock == -1 ? false : true);
		$this->extras 			  = $view->loadTemplate();

		$placeholders = array(
			'{price}' 	=> $price,
			'{buy}'		=> '',
			'{extras}'  => '',
			'{stock}'	=> ($this->item->stock > -1 ? ( $this->item->stock == 0 ? JText::_('COM_RSMEMBERSHIP_UNLIMITED') : $this->item->stock) : JText::_('COM_RSMEMBERSHIP_OUT_OF_STOCK_PLACEHOLDER')) ,
			'<hr id="system-readmore" />' => ''
		);

		$replace = array_keys($placeholders);
		$with 	 = array_values($placeholders);

		$this->item->description = str_replace($replace, $with, $this->item->description);

		// prepare the Pathway
		$pathway 		= $app->getPathway();
		$this->Itemid   = $app->input->get('Itemid', 0, 'int');
		$layout  		= $app->input->get('layout', '', 'cmd');

		if ($this->item->category_id) 
			$pathway->addItem( $this->item->category_name, JRoute::_( RSMembershipRoute::Memberships( $this->item->category_id, $this->Itemid, $layout ) ) );

		$pathway->addItem($this->item->name, '');

		// Title
		if (!$params->get('page_title')) 
			$this->document->setTitle($this->item->name);
		else 
			$this->document->setTitle($params->get('page_title').' - '.$this->item->name);

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $active = $app->getMenu()->getActive();
        if ($active
            && $active->component == 'com_rsmembership'
            && isset($active->query['view'], $active->query['cid'])
            && $active->query['view'] == 'membership'
            && $active->query['cid'] == $this->item->id)
        {
            $this->params->def('page_heading', $this->params->get('page_title', $active->title));
        }
        else
        {
            $this->params->set('page_heading', $this->item->name);
        }

		// Description
		if ($params->get('menu-meta_description')) 
			$this->document->setDescription($params->get('menu-meta_description'));
		// Keywords
		if ($params->get('menu-meta_keywords')) 
			$this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
		// Robots
		if ($params->get('robots')) 
			$this->document->setMetadata('robots', $params->get('robots'));

		parent::display();
	}
}