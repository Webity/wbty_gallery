<?php
/**
 * @package AkeebaReleaseSystem
 * @copyright Copyright (c)2010-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: ars.php 123 2011-04-13 07:47:16Z nikosdion $
 */

// no direct access
defined('_JEXEC') or die('');

jimport('joomla.installer.installer');
$db = & JFactory::getDBO();
$status = new JObject();
$status->modules = array();
$status->plugins = array();
$src = $this->parent->getPath('source');
$src = dirname(__FILE__);

// -- Download ID=
$db->setQuery('SELECT `id` FROM #__modules WHERE `module` = "mod_wbty_gallery"');

$id = $db->loadResult();
if($id)
{
	$installer = new JInstaller;
	$result = $installer->uninstall('module',$id,1);
	$status->modules[] = array('name'=>'mod_wbty_gallery','client'=>'site', 'result'=>$result);
}

?>

<h1>Webity Gallery - Image Component & Module</h1>

<h2>Uninstalled</h2>

<p>You have successfully uninstalled the Webity Gallery Component and Module.</p>