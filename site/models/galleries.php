<?php

/**
 * @version     1.0.0
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Wbty_gallery records.
 */
class Wbty_galleryModelgalleries extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JControllerLegacy
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->get('limitstart', 0, 'int');
        $this->setState('list.start', $limitstart);

        // List state information.
        parent::populateState();
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__categories` AS a');
		$query->where('a.extension = "com_wbty_gallery"');
		
		// Filters from menu settings
        $params = JFactory::getApplication()->getParams('com_wbty_gallery');
        if ($base = $params->get('base_filter')) {
            $galleries = $params->get('galleries');
            if ($galleries) {
				
				// Grab child gallery IDs
				if ($this->state->get('parameters.menu')->get('children')) {
					jimport( 'joomla.application.categories' );
					$categories = JCategories::getInstance('Wbty_gallery');
					$this->_parent = $categories->get($galleries[0]);
					if(is_object($this->_parent)) {
						$this->_items = $this->_parent->getChildren();
						if ($this->_items) {
							foreach ($this->_items as $item) {
								$children[] = $item->id;
							}
						}
					}
					
					if ($this->state->get('parameters.menu')->get('exclude_parents') && $children) {
						$galleries = $children;
					} else {
						array_push($galleries, $children);
					}
					
				}
				
                if ($base == 2) {
                    $query->where('a.id NOT IN ('.implode(',', (array)$galleries).')');
                } else {
                    $query->where('a.id IN ('.implode(',', (array)$galleries).')');
                }
            }
        }
        if ($limit = $params->get('limit')) {
            $this->setState('list.limit', $limit);
        }
        if ($orderby = $params->get('orderby')) {
            if ($orderby == 'alpha') {
                $query->order('a.title ASC');
            } else {
                $query->order('a.created_time DESC');
            }
        }
        
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = '.(int) $published);
        } else if ($published === '') {
            $query->where('(a.state = 1 )');
        }
        



        return $query;
    }
	
	public function getItems() {
		$query	= $this->_db->getQuery(true);
		
		$items = parent::getItems();
		
		$item_count = count($items);
        
		// Get the associated pieces and add them to each gallery
		for ($i = 0; $i < $item_count; $i++) {
			$query->clear();
			$query->select('a.id');
			$query->from('#__categories AS a');
			$query->where('a.extension = "com_wbty_gallery"');
			$query->where('a.lft >= '.$items[$i]->lft);
			$query->where('a.rgt <= '.$items[$i]->rgt);
			$ids = $this->_db->setQuery($query)->loadColumn();
			
			if ($ids) {
    			$query->clear();
    			$query->select('i.image, i.id');
    			$query->from('#__wbty_gallery_images AS i');
    			$query->where('i.category IN ('.implode(',', $ids).')');
    			$query->where('i.image != \'\'');
    			$query->where('i.state = 1');
                $query->where('i.base_id = 0');
			
    			$pieces = $this->_db->setQuery($query)->loadAssocList();
    			$items[$i]->pieces = $pieces;
            }
		}
		return $items;
	}

}
