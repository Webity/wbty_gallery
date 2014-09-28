<?php
/**
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Email cloack plugin class.
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.wbty_gallery
 */
class plgContentWbty_gallery extends JPlugin
{
    var $script_added = false;
    
    public function onContentPrepare($context, &$row, &$params, $page = 0)
    {
        // Don't run this plugin when the content is being indexed
        if ($context == 'com_finder.indexer') {
            return true;
        }

        if (is_object($row)) {
            return $this->_scan($row->text);
        }
        return $this->_scan($row);
    }

    protected function _scan(&$text)
    {
        $parts = array();
        $parts[] = '(<[^\/]{0,}>{wbty_gallery_slideshow)';
        $parts[] = '(?!<[A-Za-z])(.*)';
        $parts[] = '(}(.*?))';
        $parts[] = '(?!<[A-Za-z])(.*?)';

        $new_text = preg_replace_callback('/'.implode($parts).'/siU',
                      array(get_class($this),'_buildSlideshow'),
                      $text, -1, $count);
        // tends to throw "too many backlinks" error, so this skips it on error
        // currently working on a fix to help performance

        if ($new_text) {
            $text = $new_text;
        }

        $parts = array();
        $parts[] = '(<[^\/]{0,}>{wbty_gallery)';
        $parts[] = '(?!<[A-Za-z])(.*)';
        $parts[] = '(}(.*?))';
        $parts[] = '(?!<[A-Za-z])(.*?)';

        $new_text = preg_replace_callback('/'.implode($parts).'/siU',
                      array(get_class($this),'_buildGallery'),
                      $text, -1, $count);
        // tends to throw "too many backlinks" error, so this skips it on error
        // currently working on a fix to help performance

        if ($new_text) {
            $text = $new_text;
        }

        
        $text = preg_replace('/(<[^\/]{0,}>{wbty_gallery}(.*))(<[A-Za-z])/siU', '$3', $text);
        $text = preg_replace('/(<[^\/]{0,}>{\/wbty_gallery}(.*))(<[A-Za-z])/siU', '$3', $text);

        return true;
    }

    protected function processAttributes($attr) {
        $x = new SimpleXMLElement("<element $attr />");
        return $x;
    }
    
    protected function _buildSlideshow($matches) {
        static $count;

        if (!$count) {
            $count = 1;
        } else {
            $count++;
        }

        $attribs = $this->processAttributes($matches[2]);

        $attr = $attribs->attributes();

        foreach($attr as $key=>$val){
            $jparams[(string)$key] = (string)$val;
        }

        require_once(JPATH_BASE . "/components/com_wbty_gallery/helpers/wbty_gallery.php");

        $the_params = Wbty_galleryHelper::buildSettings(true, $jparams);

        $alias = 'plg_wbty_gallery_slider'.$count;

        $slides = Wbty_galleryHelper::getSlides($the_params);
        Wbty_galleryHelper::buildScripts($alias, $the_params);

        $moduleclass_sfx = '';

        ob_start();
        require JModuleHelper::getLayoutPath('mod_wbty_gallery_slider', 'default');
        $output = ob_get_clean();

        return $output;
    }
    
    protected function _buildGallery($matches) {
        $attribs = $this->processAttributes($matches[2]);

        $db =& JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('*');
        $query->from('#__wbty_gallery_images as i');
        if ($attribs['id']) {
            $query->where('i.category = '.(int)$attribs['id']);
        }
        $query->join('LEFT','#__categories as c ON c.id=i.category AND c.published=1');
        $query->where('i.state=1');
        $query->order('RAND()');

        $image = $db->setQuery($query, 0, 1)->loadObject();

        ob_start();
        require JModuleHelper::getLayoutPath('mod_wbty_gallery', 'default');
        $output = ob_get_clean();

        return $output;
    }
}
