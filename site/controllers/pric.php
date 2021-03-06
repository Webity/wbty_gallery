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
 * pric controller class.
 */
class Wbty_galleryControllerpric extends JControllerLegacy
{

    function __construct() {
        $this->view_list = 'price';
        parent::__construct();
    }

}