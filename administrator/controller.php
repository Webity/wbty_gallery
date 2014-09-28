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

// check for Joomla 2.5
if (!class_exists('JControllerLegacy')) {
	class JControllerLegacy extends JController {}
}

class Wbty_galleryController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/wbty_gallery.php';
		$app = JFactory::getApplication();

		// Load the submenu.
		Wbty_galleryHelper::addSubmenu($app->input->get('view', 'controlpanel'));

		$view		= $app->input->get('view', 'controlpanel');
        $app->input->set('view', $view);

		parent::display();

		return $this;
	}
}
