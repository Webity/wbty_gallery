<?php
/**
 * @version     1.0.1
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access
defined('_JEXEC') or die;
jimport('wbty_components.tables.wbtytableversioning');

/**
 * image Table class
 */
class Wbty_galleryTableimages extends WbtyTableVersioning
{
	
	public function __construct(&$db)
	{
		parent::__construct('#__wbty_gallery_images', 'id', $db);
	}

    public function check() {

        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder('category='.$this->category);
        }
        
        // pass true to prevent parent from overwriting ordering set
        return parent::check(true);
    }

}
