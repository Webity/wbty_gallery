<?php
/**
 * @version     1.0.0
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

/**
 * Wbty_gallery model.
 */
class Wbty_galleryModelpric extends JModelItem
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = JFactory::getApplication()->input->getInt('id');
		$this->setState('pric.id', $pk);

		$offset = JFactory::getApplication()->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// TODO: Tune these values based on other permissions.
		$user		= JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_wbty_gallery')) &&  (!$user->authorise('core.edit', 'com_wbty_gallery'))){
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}
	}
        
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('pric.id');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {

                        $db = $this->getDbo();
                        $query = $db->getQuery(true);

                        $query->select($this->getState(
                                'item.select', 'a.*'
                                )
                        );
                        $query->from('#__wbty_gallery_price AS a');
                        
                        $query->where('a.id = '. (int) $pk);

                        // Filter by published state.
                        $published = $this->getState('filter.published');
                        $archived = $this->getState('filter.archived');

                        if (is_numeric($published)) {
                                $query->where('(a.state = ' . (int) $published . ' OR a.state =' . (int) $archived . ')');
                        }

                        $db->setQuery($query);

                        $data = $db->loadObject();

                        if ($error = $db->getErrorMsg()) {
                                JError::raiseError(404, $error);
                                return false;
                        }

                        $this->_item[$pk] = $data;
			
		}

		return $this->_item[$pk];
	}

}