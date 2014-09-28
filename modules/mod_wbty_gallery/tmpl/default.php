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

<div class="wbty_gallery_thumbnails">
<?php
foreach($images as $image):
if($image->image):
	$image_location = JURI::root(true);
	switch($params->get('size'))
	{
		case "SMALL":
			$ext = strtolower(strrchr($image->image, '.'));
			$image_location .= '/media/wbty_gallery/thumbs/' . $image->id . '-small' . $ext;
		break;
		case "MEDIUM":
			$ext = strtolower(strrchr($image->image, '.'));
			$image_location .= '/media/wbty_gallery/thumbs/' . $image->id . '-medium' . $ext;
		break;
		case "LARGE":
			$ext = strtolower(strrchr($image->image, '.'));
			$image_location .= '/media/wbty_gallery/thumbs/' . $image->id . '-large' . $ext;
		break;
		case "FULL":
			$image_location .= '/' . $image->image;
		break;
	}

?>
	<a class="wbty_thumbnail_link" <?php echo (($params->get('image_link') == 'true') ? 'href="' . JRoute::_('index.php?option=com_wbty_gallery&view=images&id='.$image->category) . '#wbty_gallery/' . $image->id . '"' : ''); ?>>
		<img 
			src="<?php echo $image_location; ?>"
			alt="<?php echo $image->name . ' - ' . $image->category_title; ?>"
		 />
	</a>

<?php

endif;
endforeach;

?>
<?php if($params->get('link') == 'true'): ?>
	<p><a href="<?php echo JRoute::_('index.php?option=com_wbty_gallery&view=images&id='.$image->category); ?>">
		<?php echo $params->get('link_title'); ?>
	</a></p>
<?php endif; ?>
</div>