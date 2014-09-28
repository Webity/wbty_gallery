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

jimport('joomla.application.component.controller');

/**
 * pricing_set controller class.
 */
class Wbty_galleryControllerpricing_set extends JControllerLegacy
{

    function __construct() {
        $this->view_list = 'pricing_sets';
        parent::__construct();
    }
	
	function options() {
		if (!$id = JFactory::getApplication()->input->get('id')) {
			echo '';
			exit();
		}
		
		$model = $this->getModel('pricing_set');
		
		$price_set = $model->getItem();
		
		$string = '';
		foreach ($price_set->prices as $price) {
			$string .= '<option value="'.$price->id.'">'.$price->option_name.' - $'.$price->price.'</option>';
		}
		
		echo $string;
		exit();
	}

}