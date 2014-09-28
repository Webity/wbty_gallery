<?php
/**
 * @version     1.0.1
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

?>

<div class="cpanel">
	<h2>Main Tasks</h2>
    <div class="icon-wrapper">
        
				<div class="btn cpanel-btn">
					<a href="index.php?option=com_wbty_gallery&view=images"><img src="<?php echo JURI::root(); ?>media/wbty_gallery/img/images.png" alt=""><span>Images</span></a>
				</div>
				<div class="btn cpanel-btn">
					<a href="index.php?option=com_categories&extension=com_wbty_gallery"><img src="<?php echo JURI::root(); ?>media/wbty_gallery/img/categories.png" alt=""><span>Categories</span></a>
				</div>
        <div class="clr"></div>
    </div>
    <h2 style="clear:left;">Configuration / Settings</h2>
    <div class="icon-wrapper">
        
        <div class="clr"></div>
    </div>
</div>