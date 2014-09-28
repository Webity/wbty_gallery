<?php
/**
 * @version     1.0.0
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */

class Wbty_galleryHelper
{
	static function buildScripts($alias, $params) {
		$doc =& JFactory::getDocument();
		
		if (!JFactory::getApplication()->input->get('jquery')) {
			if ( $params->includeJquery == 1 ) {
				if ( $params->jqueryMethod == 0 ) {
					$doc->addScript("//code.jquery.com/jquery-1.9.1.js");
					$doc->addScript("//code.jquery.com/ui/1.10.3/jquery-ui.js");
				} else {
					$doc->addScript(JURI::root(true) . "/media/wbty_gallery/js/jquery-1.10.1.min.js");
				}
				JFactory::getApplication()->input->set('jquery', 1);
			}
		}
		
		if ($params->fx=='rotate') { // �BER ROTATE
		
			if (!$params->width) {
				$params->width = 100;
			}
			if (!$params->height) {
				$params->height = 100;
			}
			
			$javascript = "
			
			jQuery(document).ready(function(wbty) {
				
				wbty('#".$alias."').parent().prepend('<div id=\"next-button\">NEXT</div><div id=\"prev-button\">PREV</div>');
				wbty('#".$alias." li').removeClass('active').removeClass('prev');
				wbty('#".$alias." li').first().addClass('active');
				wbty('#".$alias." li').last().addClass('prev');
				
				wbty('#next-button').live('click', function() {
					wbty('#".$alias." li.prev').removeClass('prev');
					wbty('#".$alias." li.active').removeClass('active').addClass('prev').next('li').addClass('active');	
					if (wbty('#".$alias." li.active').length==0) {
						wbty('#".$alias." li').first().addClass('active');
					}
				});
				
				wbty('#prev-button').live('click', function() {
					wbty('#".$alias." li.active').removeClass('active');
					wbty('#".$alias." li.prev').removeClass('prev').addClass('active').prev('li').addClass('prev');
					if (wbty('#".$alias." li.prev').length==0) {
						wbty('#".$alias." li').last('li').addClass('prev');
					}
				});
				
				function slideResize() {
					 windowWidth = wbty(window).width();
					 var newHeight = 0;
					
					if (windowWidth > 1200) {
						var heightAdjust = ".$params->width." / 1170;
						newHeight = ".$params->height." / heightAdjust;
					}
					
					if (windowWidth < 1200) {
						newHeight = ".$params->height.";
					} 
					
					if (windowWidth < 980) {
						var heightAdjust = ".$params->width." / 724;
						newHeight = ".$params->height." / heightAdjust;
					} 
					
					if (windowWidth < 768) {
						var heightAdjust = ".$params->width." / windowWidth;
						newHeight = ".$params->height." / heightAdjust;
					}
					
					if(newHeight > 0) {
						wbty('#$alias').css('height', newHeight);
						wbty('#$alias img').css('margin', 0);
					}
				 };
				 
				jQuery(window).load(function() {
					slideResize();
				});
				
				jQuery(window).resize(function() {
					slideResize();
				});
				
			});
			
			";
			
			$doc->addScriptDeclaration($javascript);
			
			$css = "
			#next-button {
				position: absolute;
				top: 0;
				right: 0;
				z-index: 90000;
			 }
			 #prev-button {
				position: absolute;
				top: 0;
				left: 0;
				z-index: 90000;
			 }
			/* images */
			  #{$alias} .prev {
				-webkit-transform: rotate(-45deg);
				-moz-transform: rotate(-45deg);
				-ms-transform: rotate(-45deg);
				-o-transform: rotate(-45deg);
				transform: rotate(-45deg);
			 }
			  #{$alias} .active {
				 -webkit-transform:none;
				 -moz-transform:none;
				 -ms-transform:none;
				 -o-transform:none;
				 transform:none;
				 
				 opacity:1;
				 visibility:visible
			 }
			  #{$alias} li {
				-webkit-transition: all 0.5s ease-in-out;
				-moz-transition: all 0.5s ease-in-out;
				-ms-transition: all 0.5s ease-in-out;
				-o-transition: all 0.5s ease-in-out;
				transition: all 0.5s ease-in-out;
				
				-webkit-transform: rotate(45deg) translateZ(1px);
				-moz-transform: rotate(45deg) translateZ(1px);
				-ms-transform: rotate(45deg) translateZ(1px);
				-o-transform: rotate(45deg) translateZ(1px);
				transform: rotate(45deg) translateZ(1px);
				
				-webkit-backface-visibility: hidden;
				-moz-backface-visibility: hidden;
				backface-visibility: hidden;
				visibility: hidden;
				
				opacity: 0;
				width: 0;
				
				position:absolute;
				top:50%;
				left:0;
				width:100%;
				height:100%;
			 }
			  #{$alias} {
				 overflow: hidden;
				 text-align: center;
				 list-style: none;
				 padding: 0;
				 margin: 0;
				 position:relative;
			 }
			  #{$alias} img {
				 position:relative;
				 max-width: 100%;
				 top: -50%;
			}
			";
			
			$css .= $params->customCSS . "\n";
			$doc->addStyleDeclaration($css);
		
		} else {
			return;
			$doc->addScript( JURI::root(true) . "/media/wbty_gallery/js/jquery.cycle.all.min.js");
			JHTML::stylesheet('wbty_gallery/fade.css', false, true);
			$javascript = "
			
			var centerY = function(element) {
				jQuery(element).each(function() {
					var eHeight = jQuery(this).outerHeight();
					var pHeight = jQuery(this).parent().outerHeight();
					jQuery(this).css('margin-top', (pHeight - eHeight) / 2);
				});
			}
		
			jQuery(document).ready(function(){
			";
												  
			if ($params->captionPos == 'center') $javascript .= "centerY('.caption-center');";
				
			if ($params->buildArrows) {
				$javascript .= "
					jQuery('#$alias').parent().prepend('<div id=\"next-button\" class=\"navigation\">NEXT</div><div id=\"prev-button\" class=\"navigation\">PREV</div>');
					centerY(jQuery('.wbtyslider_wrap .navigation'));";
			}
											  
			$javascript .= "
				jQuery(window).load(function() {
					window.wbty_galleryresize = setTimeout(function() {slideResize();}, 500);
					centerY(jQuery('.wbtyslider_wrap .navigation'));
					".($params->captionPos == "center" ? "centerY('.caption-center');" : '')."
				});
				
				jQuery(window).resize(function() {
					window.wbty_galleryresize = setTimeout(function() {slideResize();}, 500);
					centerY(jQuery('.wbtyslider_wrap .navigation'));
					".($params->captionPos == "center" ? "centerY('.caption-center');" : '')."
				});
				
				
				jQuery('#$alias').cycle({";
			$javascript .= "width : '100%',\n"; //s" . $params->width . "',\n";
			$javascript .= "fit : '" . $params->fit . "',\n";
			$javascript .= "height : '" . ($params->height ? $params->height : 'auto') . "',\n";
			if ($params->fx != 'fade') {
				$javascript .= "fx: '" . $params->fx . "',\n";
			}
			$javascript .= "pause:  " . (int)$params->pause . ",\n";
			$javascript .= "cssBefore: 'max-width: 100%;',\n";
			if ($params->buildArrows) {
				$javascript .= "next : '#next-button',\n";
				$javascript .= "prev : '#prev-button',\n";
			}
			$javascript .= "timeout:  " . (int)$params->timeout . ",\n";
			if ($params->speed !== '') {
				$javascript .= "speed:  " . (int)$params->speed . ",\n";
			}
			if ($params->delay !== '') {
				$javascript .= "delay: " . (int)$params->delay ."\n";
			}
			$javascript .= "});";
			
			if ($params->height) {
				$javascript .= "
				
				function slideResize() {

					if (jQuery('#$alias .content-slide').length > 0) {
						
						var tallest = newHeight = 0;
						var parentHeight = jQuery('#$alias').height();
						jQuery('#$alias').height(20000);
						jQuery('#$alias li').each(function() {
							newHeight = jQuery(this).css({position : 'relative', height : 'auto !important'}).height();
							jQuery(this).css({position : 'absolute'});
							if (newHeight > tallest) tallest = newHeight;
						});
						jQuery('#$alias, #$alias li').height(tallest);
					
					} else {
					
						var newHeight = 0;
						var heightAdjust = ".$params->width." / jQuery('#$alias').parent().width();
						
						newHeight = ".$params->height." / heightAdjust;
						
						if(newHeight > 0) {
							jQuery('#$alias').css('height', newHeight);
							jQuery('#$alias img').css('margin', 0).css('width', '100%');
						}
					}
				 };
				\n";
			} else {
				$javascript .= "
				
				function slideResize() {
				}
				\n";
			}
			
			if ($params->buildThumbs == 1) {
				$javascript .= "
					
				jQuery('.image-thumb img').click(function(e) {
					jQuery(this).closest('.wbty_gallery_wrap').find('ul').first().cycle(parseInt(jQuery(this).attr('data-count')));
					return false;
				});
				";
			}
			
			$javascript .= "});";
			
			$doc->addScriptDeclaration( $javascript );
			$css = "#{$alias} {
			  max-width: 100%;
			  /*width: ".( $params->width ? $params->width : 0 ) . "px;
			  height: ".( $params->height ? $params->height : 0 ) . "px;*/
			  overflow: hidden;
			  list-style: none;
			 }";
			 
			 $css .= "/* images with caption */
			 #{$alias} li {
			  width: 100%;
			  height: 100% !important;
			 }
			 /* position the panels so the captions appear correctly */
			 #{$alias} .panel { position: relative; }
			 /* captions */
			 #{$alias} .caption-top, #{$alias} .caption-right,
			 #{$alias} .caption-bottom, #{$alias} .caption-left {
			  background: #fff;
			  color: #000;
			  padding: 10px;
			  margin: 0;
			  position: absolute;
			  z-index: 10;
			  background: #fff;
			  background: rgba(255,255,255,.8);
			  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ccffffff', endColorstr='#ccffffff',GradientType=0 );
			 }
			 /* Top caption - padding is included in the width (480px here, 500px in the script), same for height */
			 #{$alias} .caption-top {
			  left: 0;
			  top: 0;
			  width: 100%;
			  height: 30px;
			 }
			 /* Right caption - padding is included in the width (130px here, 150px in the script), same for height */
			 #{$alias} .caption-right {
			  right: 0;
			  bottom: 0;
			  width: 130px;
			  height: ".($params->height-20)."px;
			 }
			 /* Bottom caption - padding is included in the width (480px here, 500px in the script), same for height */
			 #{$alias} .caption-bottom {
			  left: 0;
			  bottom: 0;
			  width: 100%;
			 }
			 /* Left caption - padding is included in the width (130px here, 150px in the script), same for height */
			 #{$alias} .caption-left {
			  left: 0;
			  bottom: 0;
			  width: 130px;
			  height: ".($params->height-20)."px;
			 }
			 /* Center caption */
			 #{$alias} .caption-center {
			  left: 0;
			  top: 0;
			  width: 100%;
			  position: absolute;
			  padding: 0;
			 }
			 #{$alias} .caption-center .interior {
			  width: 60%;
			  margin: 0 auto;
			  display: block;
			  background: #fff;
			  background: rgba(255,255,255,.8);
			  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ccffffff', endColorstr='#ccffffff',GradientType=0 );
			  color: #000;
			  padding: 15px;
			 }
			 /* Caption close button */
			 .caption-top .close, .caption-right .close,
			 .caption-bottom .close, .caption-left .close {
			  font-size: 80%;
			  cursor: pointer;
			  float: right;
			  display: inline-block;
			 }";
			 $doc->addStyleDeclaration($css);
		}
	}
	
	static function buildSettings($module = false, $jparams = array()) {
		jimport( 'joomla.application.component.helper' );
		$params  = JComponentHelper::getParams('com_wbty_gallery')->toObject();
		
		if (!$module) {
			//load parameters from menu for the component view
			$menuitemid = JFactory::getApplication()->input->getInt( 'Itemid' );
			if ($menuitemid) {
				$menu = JSite::getMenu();
				$jparams = $menu->getParams( $menuitemid );
				$jparams = $jparams->toObject();
			}
		}
			
		if ($jparams) {
			foreach ($jparams as $key=>$value) {
				if ($value!='-' && $value!='') {
					// CustomCSS should be added together instead of replaced
					if ($key=='customCSS') {
						$params->$key .= $value;
					} else {
						$params->$key = $value;
					}
				}
			}
		}
		
		return $params;
	}
	
	static function getSlides($params) {
		$db = &JFactory::getDBO();
		
		$query = "SELECT * FROM #__wbty_gallery_images as s 
				WHERE s.state=1 AND s.category=".(int)$params->package." 
				AND base_id = 0
				ORDER BY s.ordering, s.id";
				
		$db->setQuery( $query );
		$slides = $db->loadAssocList();
		
		return $slides;
	}
	
	static function getPricingSet($id = 0) {
		if (!$id) {
			return '';
		}
		
		$org_id = JFactory::getApplication()->input->get('id');
		JFactory::getApplication()->input->set('id', $id);
		
		$model = JModelLegacy::getInstance('pricing_set', 'Wbty_galleryModel');
		
		$price_set = $model->getItem();
		
		JFactory::getApplication()->input->set('id', $org_id);
		
		return $price_set;
	}

}

