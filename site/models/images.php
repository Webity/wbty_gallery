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
class Wbty_galleryModelimages extends JModelList {

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
        $this->params = $app->getParams('com_wbty_gallery');

        // List state information.
        parent::populateState();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $this->params->get('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->get('limitstart', 0, 'int');
        $this->setState('list.start', $limitstart);
		
		// LIMIT THAT STUFF IF NEED BE!
		if ($this->state->get('parameters.menu')) {
			if ($limit = $this->state->get('parameters.menu')->get('limit')) {
				JFactory::getApplication()->input->set('limit', $limit);
			}
		}
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
		$query->select('c.title AS category_name');
        $query->from('`#__wbty_gallery_images` AS a');
		$query->join('LEFT', '#__categories AS c ON a.category=c.id');
		
		// Filter by categories
		if ($gallery = JFactory::getApplication()->input->get('gallery_id')) {
		} else if ($gallery = JFactory::getApplication()->input->get('id')) {
		} else if ($this->state->get('parameters.menu') && $gallery = $this->state->get('parameters.menu')->get('id')) {
		} else if ($this->state->get('parameters.menu') && $galleries = $this->state->get('parameters.menu')->get('galleries')) {
			$query->where('a.category IN ('.implode(',', $galleries).')');
		}
		
		if ($galleries) {
			
		}
		
		if ($gallery) {
			$query->where('c.lft>=(SELECT cat.lft FROM #__categories as cat WHERE cat.id='.(int)$gallery.') AND c.rgt<=(SELECT cat.rgt FROM #__categories as cat WHERE cat.id='.(int)$gallery.')');
		}

        $query->where('a.base_id = 0');
        
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = '.(int) $published);
        } else {
            $query->where('(a.state = 1)');
        }
		
		$query->order('c.rgt DESC, ordering DESC');
		
        return $query;
    }
	
	public function getStart()
	{

		$start = $this->getState('list.start');
		$limit = $this->getState('list.limit');
		$total = $this->getTotal();
		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}
		
		return $start;
	}

}
