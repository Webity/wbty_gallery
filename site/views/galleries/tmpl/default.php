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
$document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js');
JHTML::stylesheet('wbty_gallery/gallery.css', false, true);

$num_galleries = count($this->items);
$num_columns = ($this->params->get('columns') ? $this->params->get('columns') : 3);
if ($num_galleries < $num_columns && $num_galleries) $num_columns = $num_galleries;

// Add any dynamic styles!
if ($num_galleries < 3) {
	$document->addStyleDeclaration('.gallery-thumb {background-size: 100% auto;}');
}
$document->addStyleDeclaration('#galleries .gallery-container {width: '. (100 - ($num_columns*5)) / $num_columns .'%}');
$document->addScriptDeclaration($script);
ob_start();
?>
	var currentTallest = 0,
		 currentRowStart = 0,
		 rowDivs = new Array(),
		 $el,
		 topPosition = 0;
	
	 $('.gallery-container').each(function() {
	
	   $el = $(this);
	   topPostion = $el.position().top;
	   
	   if (currentRowStart != topPostion) {
	
		 // we just came to a new row.  Set all the heights on the completed row
		 for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		   rowDivs[currentDiv].height(currentTallest);
		 }
	
		 // set the variables for the new row
		 rowDivs.length = 0; // empty the array
		 currentRowStart = topPostion;
		 currentTallest = $el.height();
		 rowDivs.push($el);
	
	   } else {
	
		 // another div on the current row.  Add it to the list and check if it's taller
		 rowDivs.push($el);
		 currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
	
	  }
	   
	  // do the last row
	   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		 rowDivs[currentDiv].height(currentTallest);
	   }
	   
	 });
<?php
$equal_height_script = ob_get_clean();
$document->addScriptDeclaration($equal_height_script);
?>

<div id="galleries">
    
	<?php
	
	
	$menu_param = '';
	if ($menu_id = $this->params->get('menu_item')) $menu_param = '&Itemid='.$menu_id;
	echo $menu_param;
	
    $html = '';
    foreach ($this->items as $gallery) {
		$html .= '<div class="gallery-container">';
		if ($gallery->pieces) {
			if($this->params->get('gallery_cover') == 'first') {
				$image = $gallery->pieces[0];
			} else {
				$image = $gallery->pieces[rand(0, count($gallery->pieces) - 1)];
			}
			$ext = strtolower(strrchr($image['image'], '.'));
			$medium_thumb = '/media/wbty_gallery/thumbs/'.$image['id'].'-medium'.$ext;
			//if (!file_exists(JURI::root(true) . $medium-thumb)) $medium_thumb = $image['image'];
			$bg_css = ' style="background-image: url('.JURI::root(true) . $medium_thumb.');"';
		
    
			
								$html .= '<a href="'.JRoute::_('index.php?option=com_wbty_gallery&view=images&layout=columns'.$menu_param.'&id='.$gallery->id).'"><div class="gallery-thumb"'.$bg_css.'></div></a>
								';
		}
		$html .= '<div class="gallery-title" style="text-align:center;">
					<a href="'.JRoute::_('index.php?option=com_wbty_gallery&view=images&layout=columns'.$menu_param.'&id='.$gallery->id).'" class="gallery-title">'.$gallery->title.'</a>
				</div>
		</div>';
    }
    
	echo $html;
    ?>
        
    <div class="clear"></div>
</div>