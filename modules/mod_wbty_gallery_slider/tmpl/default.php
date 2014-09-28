<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_footer
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<div class="wbty_gallery_wrap wbty_gallery_module <?php echo $moduleclass_sfx; ?>">

<?php
// load all the html from the component
require(JPATH_BASE . '/components/com_wbty_gallery/views/slideshow/tmpl/default_slider.php');
require(JPATH_BASE . '/components/com_wbty_gallery/views/slideshow/tmpl/default_thumbnails.php');
?>

</div>
