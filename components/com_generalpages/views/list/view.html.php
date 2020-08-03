<?php
/**
 * @version     1.0.0
 * @package     com_generalpages
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      360South Pty Ltd <tech@360south.com.au> - http://www.360south.com.au/
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Generalpages.
 */
class GeneralpagesViewList extends JViewLegacy
{
	protected $items;Í
	protected $pagination;
	protected $state;
    protected $params;

    public $keyword;
	public $authorId;
	public $typeId;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $app                = JFactory::getApplication();
        
        $this->state		= $this->get('State');
        $this->items		= $this->get('Items');
        $this->generalpages		= $this->get('Generalpages');
        $this->pagination	= $this->get('Pagination');
        $this->params       = $app->getParams('com_generalpages');
        

        $this->keyword          = JRequest::getVar('keyword');
		$this->authorId 		= JRequest::getVar('author');
		$this->typeId           = JRequest::getVar('type');
		$this->sort             = JRequest::getVar('sort');
		$this->order            = JRequest::getVar('order');

		$this->Itemid = JRequest::getVar('Itemid');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {;
            throw new Exception(implode("\n", $errors));
        }
        
        $this->_prepareDocument();
        parent::display($tpl);
	}


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('com_generalpages_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	} 

	/**
	 * Get current search
	 */
	public function getCurrentSearch($array2 = array())
	{
		$array1 = array(
		    'Itemid'        => $this->Itemid,
		    'keyword'       => $this->keyword,
		    'author' 		=> $this->authorId,
		    'type'          => $this->typeId,
		    'sort'          => $this->sort,
		    'order'          => $this->order
		);

		if (!empty($array2)) {
			foreach (array_keys($array2) as $key) {
			    if (isset($array1[$key])) {
			    	unset($array1[$key]);
			    }
			}
			$array1 = array_merge($array1, $array2);
		}

		//$array1 = array_filter($array1);

		return JRoute::_('index.php?option=com_generalpages&' . http_build_query($array1, '', '&amp;'));
	}

	/**
	 * Sorting list
	 */
	public function getSortings()
	{
		return array(
			$this->getCurrentSearch(array('sort' => 'date', 'order' => 'DESC')) => 'New to Old',
			$this->getCurrentSearch(array('sort' => 'date', 'order' => 'ASC')) => 'Old to New',

		);
	}

	/**
	 * Get current sorting if any
	 */
	public function getCurrentSorting()
	{
		return $this->getCurrentSearch(array('sort' => $this->sort, 'order' => $this->order));
	}

	/**
	 * Get author
	 */
	public static function getAuthors() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__author'))
		    ->order('author_id ASC');
		$db->setQuery($query);
		$authors = $db->loadObjectList();

		return $authors;
	}	

	/**
	 * Get category types
	 */
	public static function getTypes() {
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
		    ->from($db->quoteName('#__generalpages_categories'))
		    ->order('ordering DESC');
		$db->setQuery($query);
		$authors = $db->loadObjectList();

		return $authors;
	}	

   
    	
}
