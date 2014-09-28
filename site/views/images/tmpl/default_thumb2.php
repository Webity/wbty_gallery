<?php
/**
 * @version     1.0.0
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */


// no direct access
defined('_JEXEC') or die;

$document = JFactory::getDocument();
JHTML::script('wbty_gallery/jquery.thumbnailScroller.js', false, true);
JHTML::script('wbty_gallery/jquery-ui-1.10.3.custom.min.js', false, true);
JHTML::stylesheet('wbty_gallery/jquery.thumbnailScroller.css', false, true);
JHTML::stylesheet('wbty_gallery/ui-lightness/jquery-ui-1.10.3.custom.css', false, true);


$javascript = "

jQuery(window).load( function () {
	jQuery('#wbty_gallery_thumbs').thumbnailScroller({ 
		scrollerType:'clickButtons', 
		scrollerOrientation:'horizontal', 
		scrollSpeed:2, 
		scrollEasing:'swing', 
		scrollEasingAmount:600, 
		acceleration:4, 
		scrollSpeed:800, 
		noScrollCenterSpace:10, 
		autoScrolling:0, 
		autoScrollingSpeed:2000, 
		autoScrollingEasing:'easeInOutQuad', 
		autoScrollingDelay:500 
	});";

$javascript .= "});";

$document->addScriptDeclaration( $javascript );

$num_photos = count($this->items);
$num_columns = ($this->params->get('columns') ? $this->params->get('columns') : 3);

?>
<div id="wbty_gallery_thumbs" class="jThumbnailScroller">
<div class="jTscrollerContainer">
<div class="jTscroller">

<?php
$i = 0;
$html = array();
foreach ($this->items as $image) {
	
	$ext = strtolower(strrchr($image->image, '.'));
	$small_thumb = '/media/wbty_gallery/thumbs/'.$image->id.'-small'.$ext;
	$large_thumb = '/media/wbty_gallery/thumbs/'.$image->id.'-large'.$ext;

	
	echo '<a href="#wbty_gallery/'.$i.'">
			<div class="image-thumb">
				<img src="'. JURI::root(true) . $small_thumb . '" data-src="'. JURI::root(true) . $large_thumb . '" data-id="'.$image->id.'" data-count="'.$i.'" data-price-set="'.($image->for_sale ? $image->pricing_set : 0).'" />';
	
	if ($this->params->get('display_title')) echo '<p class="image-title">'.$image->name.'</p><div class="image-desc">'.$image->description.'</div>';
	
	echo '</div>
		</a>';
	
	$i++;
}
?>

</div>
</div>
<a href="#" class="jTscrollerPrevButton"></a>
<a href="#" class="jTscrollerNextButton"></a>
</div>