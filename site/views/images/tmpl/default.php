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
JHTML::script('wbty_gallery/wbty_gallery.js', false, true);
JHTML::stylesheet('wbty_gallery/gallery.css', false, true);

// Add photo capabilities
$script = "";

$document->addScriptDeclaration($script);
?>

<div id="gallery-surround">
    <div class="gallery-title">
    	<?php foreach ($this->items as $item) {
			if ($item->category) {
				echo '<h2>'.$item->category_name.'</h2>';
				$firstimage = $item;
				break;
			}
		}
		if ($this->params->get('show_num')) echo '<p>'.$num_photos.' photos</p>';
		?>
    </div>
    
    <div id="showcase">
        <?php if ($this->params->get('display_title')) : ?>
    	<div class="image-desc">
            <div class="image-title">
                <h3></h3>
            </div>
            <div class="desc">
            
            </div>
        </div>
        <?php else: ?>
            <style> body #showcase .image { width: 100%; } </style>
        <?php endif; ?>
        <div class="image">
            <?php echo '<img src="' . JURI::root(true) . '/media/wbty_gallery/img/load.gif" data-count="0" />'; ?> 
            <div class="prev-image"></div>
            <div class="next-image"></div>
        </div>
        <?php if ($this->params->get('pricing')) : ?>
        <div class="pay-options">
        	<h4>Purchase this print</h4>
        	<form method="post" action="<?php echo JRoute::_('index.php?option=com_wbty_gallery&task=image.add2cart'); ?>" class="form-horizontal">
            	<div class="control-group">
                	<label class="control-label" for="quantity">Quantity</label>
                    <div class="controls">
                        <select id="quantity" name="jform[quantity]">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
            	<div class="control-group">
                	<label class="control-label" for="product">Product</label>
                    <div class="controls">
                        <select id="product" name="jform[product]">
                        </select>
                    </div>
                </div>
            	<div class="control-group">
                    <div class="controls">
	                    <input type="hidden" id="image" name="jform[image]" value="" />
                        <input type="submit" value="Add to Cart" />
                    </div>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
	
	<div id="thumbs">
        <?php 
		if ($this->params->get('thumb_layout') && file_exists(JPATH_COMPONENT . '/views/images/tmpl/default_thumb'.$this->params->get('thumb_layout').'.php')) :
			echo $this->loadTemplate('thumb'.$this->params->get('thumb_layout'));
		else :
			echo $this->loadTemplate('thumb2');
		endif;
		?>
        
        <div class="clear"></div>
        
    </div>
</div>