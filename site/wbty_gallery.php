<?php
/**
 * @version     1.0.0
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
jimport('legacy.controller.legacy');

if (!class_exists('JControllerLegacy')) {
	class JControllerLegacy extends JController {}
}
if (!class_exists('JModelLegacy')) {
	class JModelLegacy extends JModel {}
}
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

// Include base css and javascript files for the component
// Import CSS
$document = &JFactory::getDocument();
if ($_SERVER['REQUEST_URI']) {
	$document->setBase($_SERVER['REQUEST_URI']);
}

JHTML::stylesheet('wbty_gallery/wbty_gallery.css', false, true);
JHTML::stylesheet('wbty_gallery/site.css', false, true);

$jversion = new JVersion();
$above3 = version_compare($jversion->getShortVersion(), '3.0', 'ge');

if ($above3) {
	JHtml::_('bootstrap.framework');
	JHTML::stylesheet('wbty_components/ui-lightness/jquery-ui-1.10.3.custom.min.css', false, true);
	JHTML::script('wbty_components/jquery-ui-1.10.3.custom.min.js', false, true);
	if (JFactory::getApplication()->isAdmin()) {}
} else {
	JHTML::stylesheet('wbty_components/ui-lightness/jquery-ui-1.10.3.custom.min.css', false, true);
	JHTML::stylesheet('wbty_components/bootstrap.min.css', false, true);
	JHTML::stylesheet('wbty_components/font-awesome.min.css', false, true);
	JHTML::script('wbty_components/jquery-1.10.2.min.js', false, true);
	JHTML::script('wbty_components/jquery-ui-1.10.3.custom.min.js', false, true);
	JHTML::script('wbty_components/bootstrap.min.js', false, true);
}

//include backend language file for jfield labels and descriptions
$lang =& JFactory::getLanguage();
$extension = '';
$base_dir = JPATH_ADMINISTRATOR;
$language_tag = 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

$task = JFactory::getApplication()->input->get('task');

if ($task) {
	$array = explode('.', $task);

	if (count($array) > 1) {
		$controller = $array[0];

		if (!file_exists(JPATH_COMPONENT . '/controllers/' . $controller . '.php')) {
			JError::raiseError(404, 'Controller not found.');
		}
	}
}

// Execute the task.
$controller	= JControllerLegacy::getInstance('Wbty_gallery');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
