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


JHtml::script('wbty_gallery/jquery.cycle2.min.js', false, true);
JHtml::script('wbty_gallery/jquery.cycle2.caption2.min.js', false, true);
JHtml::script('wbty_gallery/jquery.cycle2.center.js', false, true);

// steps to make this work for component and module
if (isset($this)) {
	foreach($this as $name => $value) {
		$$name = $value;
	}
}
if (!isset($the_params)) {
	$the_params = $params;
}

$contentSlides = true;
foreach ($slides as $key=>$slide) {
	if ($slide['image']) {
		$contentSlides = false;
		break;
	}
}

// Insert the top border (if selected)
if ($the_params->useBorders && $the_params->borderTop) {
	$width = ($the_params->borderTopWidth ? $the_params->borderTopWidth.'px' : '100%');
	echo '<div class="wbtyslider_border_top" style="background: url('.$the_params->borderTop.') top center repeat-x; width: '.$width.'; height: '.$the_params->borderTopHeight.'px; z-index: 1;"></div>';
}

echo '<div id="'.$alias.'" class="wbtygallery-slideshow cycle-slideshow'.($contentSlides ? ' html-slideshow' : '').'"
	data-cycle-fx="fade"
    data-cycle-caption-plugin="caption2"
    data-cycle-center-horz="true"
    data-cycle-timeout="'.$the_params->timeout.'"
    data-cycle-speed="'.$the_params->speed.'"
    data-cycle-delay="'.$the_params->delay.'"
    data-cycle-fit="'.$the_params->fit.'"
    data-cycle-pause-on-hover="'.$the_params->pause.'"
    data-cycle-easing="'.$the_params->easing.'"
    ';
    if ($contentSlides) {
    	echo 'data-cycle-slides="> div"';
		echo 'data-cycle-auto-height="calc"';
    } else {
		echo 'data-cycle-center-vert="true"';
	}
    if ($the_params->width && $the_params->height) {
    	echo 'data-cycle-auto-height="'. (int)$the_params->width . ':' . (int)$the_params->height .'"';
    }
    echo '>';
    ?>

    <?php
/*$target_pref = $the_params->linkTarget;
if ($target_pref == 1) {
	$target = '_top';
} elseif ($target_pref == 2) {
	$target = '_parent';
} else {
	$target = '_self';
}
*/
foreach ($slides as $key=>$slide) {
	$slide = (array)$slide;
	if ($slide['custom_menu_link']) {
		$url = JRoute::_($slide['custom_menu_link']);
	} else if  ($slide['menu_link']) {
		$item = JFactory::getApplication()->getMenu()->getItem( $slide['menu_link'] );
		$url = JRoute::_($item->link . '&Itemid=' . $item->id);
	} else {
		$url = '';
		$link = '';
		$linkclose='';
	}

/*
	if (($the_params->resizeContents)==0) {
		if ($slide['image'] && file_exists($slide['image'])) {
			$size = getimagesize($slide['image']);
		} else {
			$size = array (0, 0);
		}
		$panelstyle .= '#'.$alias.' .panel'.$key.' {
			width: '.$size[0].'px;
			height: '.$size[1].'px;
		}
		';
		if (($the_params->captionPos)=='top') {
			$panelstyle .= '#'.$alias.' .panel'.$key.' .caption-top {
		  width: '.($size[0]-20).'px;
 		}
		';
		}
		if (($the_params->captionPos)=='right') {
			$panelstyle .= '#'.$alias.' .panel'.$key.' .caption-right {
		  height: '.($size[1]-20).'px;
 		}
		';
		}
		if (($the_params->captionPos)=='left') {
			$panelstyle .= '#'.$alias.' .panel'.$key.' .caption-left {
		  height: '.($size[1]-20).'px;
 		}
		';
		}
		if (($the_params->captionPos)=='bottom') {
			$panelstyle .= '#'.$alias.' .panel'.$key.' .caption-bottom {
		  width: '.($size[0]-20).'px;
 		}
		';
		}
		$sizetag = '';
	} else {
		$panelstyle = '';
		$size='';
		$sizetag = 'width="'.$the_params->width.'" height="'.$the_params->height.'"';
	}
	if (file_exists($slide['image']) && $the_params->height) {
		$size = getimagesize($slide['image']);
		if ($size[0]/$size[1] < ($the_params->width/$the_params->height)) {
			$sizetag = 'width="'.$the_params->width.'" style="margin-top: -' .  (($size[1]*$the_params->width/$size[0])-$the_params->height)/2 . 'px"';
		} else {
			$sizetag = 'height="'.$the_params->height.'" style="margin:0 auto;"';
		}
	} else {
		$sizetag = '';
	}*/
	if ($slide['image']) {
		if ($the_params->width && $the_params->height) {
    		echo 'data-cycle-auto-height="'. (int)$the_params->width . ':' . (int)$the_params->height .'"';
    	}
    	echo '>';
		echo '<img src="'.$slide['image'].'" '; // . $sizetag;
		if ($the_params->displayCaption) {
			if ($slide['caption']) {
				echo ' data-cycle-title="'.htmlentities('<div class="caption-wrap">' . $slide['caption']).'</div>" data-cycle-desc="" ';
			} else {
				echo ' data-cycle-overlay-template="" ';
			}
		}
		if ($url) {
			echo ' onclick="window.location = \''.$url.'\';" style="cursor:pointer;" ';
		}
		echo  ' />';
	} elseif ($contentSlides) {
		if ($url) {
			$link = '<a href="'.$url.'">';
			$linkclose = '</a>';
		} else {
			$link = $linkclose = '';
		}
		echo '<div class="'.$key.'">'.$link.'<div class="htmlslide">'.$slide['caption'].'</div>'.$linkclose.'</div>';
	}
	//echo '</li>';
}
?>
	<?php if (!$contentSlides) : ?>
		<?php if ($the_params->displayCaption) : ?>
		    <div class="cycle-overlay"></div>
		<?php endif; ?>
		<?php if ($the_params->displayNavigation) : ?>
		    <div class="cycle-pager"></div>
		<?php endif; ?>
		<?php if ($the_params->buildArrows) : ?>
		    <div class="cycle-prev"></div>
		    <div class="cycle-next"></div>
		<?php endif; ?>
	<?php endif; ?>
   <?php
echo '</div>';

// Insert the bottom border (if selected)
if ($the_params->useBorders && $the_params->borderBottom) {
	$width = ($the_params->borderBottomWidth ? $the_params->borderBottomWidth.'px' : '100%');
	echo '<div class="wbtyslider_border_bottom" style="background: url('.$the_params->borderBottom.') bottom center repeat-x; width: '.$width.'; height: '.$the_params->borderBottomHeight.'px; z-index: 1;"></div>';
}
