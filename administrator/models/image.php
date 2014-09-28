<?php
/**
 * @version     1.0.1
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('wbty_components.models.wbtymodeladmin');

/**
 * Wbty_gallery model.
 */
class Wbty_galleryModelimage extends WbtyModelAdmin
{
	protected $text_prefix = 'com_wbty_gallery';
	protected $com_name = 'wbty_gallery';
	protected $list_name = 'images';

	public function getTable($type = 'images', $prefix = 'Wbty_galleryTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true, $control='jform', $key=0)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		
		// Get the form.
		$form = $this->loadForm('com_wbty_gallery.image.'.$control.'.'.$key, 'image', array('control' => $control, 'load_data' => $loadData, 'key'=>$key));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	public function getItems($parent_id, $parent_key) {
		$query = $this->_db->getQuery(true);
		
		$query->select('id, state');
		$query->from($this->getTable()->getTableName());
		$query->where($parent_key . '=' . (int)$parent_id);
		$query->where($parent_key . '!= 0');
		$query->order('state DESC, ordering ASC');
		
		$data = $this->_db->setQuery($query)->loadObjectList();
		if (count($data)) {
			$this->getState();
			$key=0;
			foreach ($data as $key=>$d) {
				$this->data = null;
				$this->setState($this->getName() . '.id', $d->id);
				$return[$d->id] = $this->getForm(array(), true, 'jform', $d->id);
			}
		}
		
		return $return;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		if ($this->data) {
			return $this->data;
		}
		
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_wbty_gallery.edit.image.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item['image'] = parent::getItem($pk)) {

			//Do any procesing on fields here if needed
			
			
		}

		return $item;
	}

	protected function prepareTable(&$table)
	{
		$user =& JFactory::getUser();
		
		

		parent::prepareTable($table);
	}
	
	function save($data) {
		if (!parent::save($data)) {
			return false;
		}
		
		// generate thumbnail
		WbtyFileUploader::generateThumbnail($data['image'], $this->table_id, 'media/wbty_gallery/thumbs/');
		
		return $this->table_id;
	}
	
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}
		
		foreach($pks as $pk) {
			$table = $this->getTable();
			
			$table->load($pk);
			
			if (!empty($commands['category']))
			{
				$table->category = $commands['category'];
			}
			
			if (in_array($commands['for_sale'], array(0, 1)))
			{
				$table->for_sale = $commands['for_sale'];
			}
			
			if (!empty($commands['pricing_set']))
			{
				$table->pricing_set = $commands['pricing_set'];
			}
			
			$table->store();
			
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}
}