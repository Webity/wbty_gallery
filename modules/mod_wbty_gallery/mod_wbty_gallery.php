<?php defined('_JEXEC') or die('Restricted access');

$db =& JFactory::getDbo();

$query = $db->getQuery(true);

$query->select('i.*, c.title AS category_title');
$query->from('#__wbty_gallery_images as i');
if ($params->get('category')) {
	$query->where('i.category = ' . (int)$params->get('category'));
}
$query->join('LEFT','#__categories as c ON c.id=i.category AND c.published=1');
$query->where('i.state=1');
$query->order($params->get('order'));

$images = $db->setQuery($query, 0, (int)$params->get('count'))->loadObjectList();

// echo '<pre>';
// var_dump($images);
// exit();

require JModuleHelper::getLayoutPath('mod_wbty_gallery', 'default');

?>