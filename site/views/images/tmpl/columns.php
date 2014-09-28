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
JHTML::_('behavior.modal');

$pinterest = $this->params->get('pinterest_layout');
if ($pinterest) {
	JHTML::script('wbty_gallery/masonry.pkgd.min.js', false, true);
	JHTML::script('wbty_gallery/imagesloaded.pkgd.min.js', false, true);
	$script = "
	jQuery(document).ready(function ($) {
		var container = document.querySelector('#showcase');
		var msnry;
		// initialize Masonry after all images have loaded
		imagesLoaded( container, function() {
			var msnry = new Masonry( container, {
			  itemSelector: '.gallery-item'
			});
		});
	});";
	$document->addScriptDeclaration($script);
}
?>

<div id="gallery-surround">
<div id="showcase" class="masonry clearfix">
    <?php foreach ($this->items as $key => $item) : ?>
    <?php $link = '<a href="'.JRoute::_('index.php?option=com_wbty_gallery&view=image&id='.$item->id).'">'; ?>
    <div class="gallery-item<?php if ($key % $this->params->get('columns', 3) == 0) { echo ' clearer'; } ?>">
    	<div class="gallery-wrap">
            <div class="content">
            	<?php if ($this->params->get('display_title')) : ?>
                <div class="item-title">
                    <?php echo '<'.$this->params->get('title_level').'>'; $link.$item->name; ?></a><?php echo '</'.$this->params->get('title_level').'>'; ?>
                </div>
                <?php endif; ?>
                <?php
                if ($this->params->get('display_image', 1) && $item->image) {
					$ext = strtolower(strrchr($item->image, '.'));
					$medium_thumb = '/media/wbty_gallery/thumbs/'.$item->id.'-medium'.$ext;
					$large_thumb = '/media/wbty_gallery/thumbs/'.$item->id.'-large'.$ext;
					
					//if (!file_exists(JURI::root(true) . $medium-thumb)) $medium_thumb = $item->image;
					//if (!file_exists(JURI::root(true) . $large-thumb)) $large_thumb = $item->image;
					
					echo '<div class="main-image">'.$link.'<img src="'. JURI::root(true) . $medium_thumb . '" /></a>';
					if ($this->params->get('image_modal')) {
						echo '<a href="'.JURI::root(true) . $large_thumb.'" class="fulllink visible-phone"></a>';
						echo '<a href="'.JURI::root(true) . $large_thumb.'" class="fulllink modal hidden-phone"></a>';
					}
					echo '</div>';
				}
				?>
                <?php
                if ($this->params->get('display_caption', 1) && $item->caption) {
					echo '
							<div class="main-caption">
								'.$item->caption.'
							</div>
						';
				}
				?>
                <?php
                if ($this->params->get('display_custom_fields') && file_exists(JPATH_BASE . '/components/com_wbty_custom_fields/helpers/loader.php')) {
					require_once(JPATH_BASE . '/components/com_wbty_custom_fields/helpers/loader.php');
					
					echo Wbty_custom_fieldsHelperLoader::loadValues('com_wbty_gallery', 'images', $this->item->id);
				}
				?>
                <?php if ($this->params->get('display_description', 1)) : ?>
                <div class="desc">
                <?php $content = explode('<hr id="system-readmore" />', $item->content); echo $content[0]; ?>
                </div>
                <div class="readmore">
                	<a href="<?php echo JRoute::_('index.php?option=com_wbty_gallery&view=image&id='.$item->id); ?>">Read more</a>
                </div>
                <?php endif; ?>
            </div>
            <div class="pay-options" style="display:none;">
            <?php if ($item->for_sale) : ?>
                <h4>Purchase this item</h4>
                <form method="post" action="<?php echo JRoute::_('index.php?option=com_wbty_gallery&task=image.add2cart'); ?>" class="form">
                    <div class="control-group">
                        <label class="control-label" for="quantity">Quantity</label>
                        <div class="controls">
                            <input type="text" id="quantity" name="jform[quantity]" value="1" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product">Options</label>
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
            <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<style>
.gallery-item {
	float:left;
	width: <?php echo 100/$this->params->get('columns', 3); ?>%;	
}
.gallery-item.clearer { clear:left; }
.gallery-item .gallery-wrap { padding: 0 2%; }

.gallery-item img { max-width:100%; }
.gallery-item .title h3 { font-size:70%; line-height:1.2em;}
</style>
    <div class="clear"></div>
    <div class="pagination">
        <p class="counter">
                <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
</div>