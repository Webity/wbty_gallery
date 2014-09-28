<?php defined('_JEXEC') or die('Restricted access');

require_once(JPATH_BASE . "/components/com_wbty_gallery/helpers/wbty_gallery.php");

$jparams = $params->toObject();
$the_params = Wbty_galleryHelper::buildSettings(true, $jparams);

$alias = 'mod_wbty_gallery_slider'.$module->id;

$slides = Wbty_galleryHelper::getSlides($the_params);
Wbty_galleryHelper::buildScripts($alias, $the_params);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_wbty_gallery_slider', $params->get('layout', 'default'));

?>