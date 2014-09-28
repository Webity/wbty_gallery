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
?>

<ul class="itemlist">
            
	
					<li><?php echo JText::_('COM_WBTY_GALLERY_FORM_LBL_IMAGES_NAME'); ?>: <?php echo $this->item->name; ?></li>
					<li><?php echo JText::_('COM_WBTY_GALLERY_FORM_LBL_IMAGES_IMAGE'); ?>: <?php echo $this->item->image; ?></li>
					<li><?php echo JText::_('COM_WBTY_GALLERY_FORM_LBL_IMAGES_CAPTION'); ?>: <?php echo $this->item->caption; ?></li>
					<li><?php echo JText::_('COM_WBTY_GALLERY_FORM_LBL_IMAGES_CATEGORY'); ?>: <?php echo $this->item->category; ?></li>
					<li><?php echo JText::_('COM_WBTY_GALLERY_FORM_LBL_IMAGES_MENU_LINK'); ?>: <?php echo $this->item->menu_link; ?></li>
					<li><?php echo JText::_('COM_WBTY_GALLERY_FORM_LBL_IMAGES_FOR_SALE'); ?>: <?php echo $this->item->for_sale; ?></li>
					<li><?php echo JText::_('COM_WBTY_GALLERY_FORM_LBL_IMAGES_PRICING_SET'); ?>: <?php echo $this->item->pricing_set; ?></li>

</ul>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_gallery{parent_url}&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="image-form" class="form-validate form-horizontal">
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="option" id="option" value="com_wbty_gallery" />
    <input type="hidden" name="form_name" id="form_name" value="image" />
    <?php echo JHtml::_('form.token'); ?>
</form>