<?php

/**
 * @version     1.0.0
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class Wbty_galleryViewimage extends JViewLegacy {

    protected $state;
    protected $item;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$app	= JFactory::getApplication();
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->params = $app->getParams('com_wbty_gallery');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
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
			$this->params->def('page_heading', JText::_('com_wbty_gallery_DEFAULT_PAGE_TITLE'));
		}
		
		// Get the item title
		$title = $this->item->page_heading;
		
		if (empty($title)) {
			// Try and get the parameter page title
			if (!($title = $this->params->get('page_title', ''))) {
				// Get the sitename as a last resort
				$title = $app->getCfg('sitename');
			}
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
		
		if ($this->item->metadesc) {
			$this->document->setDescription($this->item->metadesc);
		} elseif (!$this->item->metadesc && $this->params->get('menu-meta_description')) {
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		
		if ($this->item->metakey) {
			$this->document->setMetadata('keywords', $this->item->metakey);
		} elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}        
    
}
