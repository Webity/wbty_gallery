<?php
/**
 * @version $Id: wbtyslider.php 2011-01-25 12:41:40 svn $
 * @package    Wbtyslider
 * @subpackage Base
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     David Fritsch {@link fritschservices.com}
 * @author     Created on 27-May-2011
 * @license    GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('=;)');

// steps to make this work for component and module
if (isset($this)) {
	foreach($this as $name => $value) {
		$$name = $value;
	}
}
if (!isset($the_params)) {
	$the_params = $params;
}

if ($the_params->buildThumbs) { 
?>
<div id="<?php echo $alias; ?>_thumbs" class="thumbnails-surround">
	<?php
    	$document = JFactory::getDocument();
		JHTML::script('wbty_gallery/jquery.thumbnailScroller.js', false, true);
		JHTML::stylesheet('wbty_gallery/jquery.thumbnailScroller.css', false, true);
		
		$javascript = "
		jQuery(window).load( function () {
			jQuery('#".$alias."_thumbs .jThumbnailScroller').thumbnailScroller({ 
				scrollerType:'clickButtons', 
				scrollerOrientation:'horizontal', 
				scrollSpeed:2, 
				scrollEasing:'easeOutCirc', 
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
		
		?>
		<a href="#" class="jTscrollerPrevButton"></a>
		<div class="jThumbnailScroller">
		<div class="jTscrollerContainer">
		<div class="jTscroller">
		
		<?php
		$i = 0;
		$html = array();
		foreach ($slides as $image) {
			$ext = strtolower(strrchr($image['image'], '.'));
			$small_thumb = '/media/wbty_gallery/thumbs/'.$image['id'].'-small'.$ext;
			$large_thumb = '/media/wbty_gallery/thumbs/'.$image['id'].'-large'.$ext;
		
			
			echo '<a href="#wbty_gallery/'.$i.'">
					<div class="image-thumb">
						<img src="'. JURI::root(true) . $small_thumb . '" data-src="'. JURI::root(true) . $large_thumb . '" data-id="'.$image->id.'" data-count="'.$i.'" />
						<p class="image-title">'.$image->name.'</p>
						<div class="image-desc">'.$image->description.'</div>
					</div>
				</a>';
			
			$i++;
		}
		?>
		
		</div>
		</div>
		</div>
		<a href="#" class="jTscrollerNextButton"></a>
	<div class="clear"></div>
</div>
<?php
}?>