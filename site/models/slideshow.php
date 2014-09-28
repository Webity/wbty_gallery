<?php
/**
 * @version     1.0.0
 * @package     com_wbtyprospects
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
jimport('joomla.application.component.modelform');

JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_wbtyprospects/tables');

/**
 * Model
 */
class Wbty_galleryModelSlideshow extends JModelLegacy
{
	protected $_item;
	
	function buildSettings() {
		if ($this->_params) {
			return $this->_params;
		}
		$this->_params = Wbty_galleryHelper::buildSettings();
		
		return $this->_params;
	}
	
	function getSlides() {
		if (!$this->_params) {
			$this->buildSettings();
		}
		
		$slides = Wbty_galleryHelper::getSlides($this->_params);
		
		return $slides;
	}
	
	function buildAlias($name = 'wbtyslider') {
		return strtolower(str_replace(" ", "_", preg_replace("[^A-Za-z0-9 ]", "", strip_tags($name))));
	}

}

