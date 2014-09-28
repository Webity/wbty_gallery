<?php
/**
 * @version $Id: wbtyslider.php 2011-01-25 12:41:40 svn $
 * @package    Wbtyslider
 * @subpackage Base
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     David Fritsch {@link fritschservices.com}
 * @author     Created on 27-May-2011
 * @license    GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('=;)');
jimport( 'joomla.application.component.view');
/**
 * HTML View class for the Wbtyslider Component
 *
 * @package    Wbtyslider
 * @subpackage Views
 */
class Wbty_galleryViewSlideshow extends JViewLegacy
{
    /**
     * Wbtyslider view display method
     *
     * @return void
     **/
    function display($tpl = null)
    {
		
		$this->loadHelper('wbty_gallery');
		
		$model = &$this->getModel();
		
		$this->params = $model->buildSettings();
		
		$this->slides = $this->get('slides');
		
		$this->alias = $model->buildAlias();
		
		Wbty_galleryHelper::buildScripts($this->alias, $this->params);
		
		
        parent::display($tpl);
    }//function
}//class
