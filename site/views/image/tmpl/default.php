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
?>
<?php if( $this->item ) : $item = $this->item; ?>
<div class="gallery-item">
    <div class="gallery-wrap">
        <div class="content">
            <?php if ($this->params->get('display_title')) : ?>
            <h2 class="title"><?php echo $item->name; ?></h2>
            <?php endif; ?>
            
            <?php if ($item->category) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_wbty_gallery&view=images&layout=columns&id='.$item->category); ?>" class="btn">View all items in Category</a>
            <?php endif; ?>
            
            <?php 
			if ($this->params->get('display_image', 1) && $item->image) {
				$ext = strtolower(strrchr($item->image, '.'));
				$small_thumb = '/media/wbty_gallery/thumbs/'.$item->id.'-small'.$ext;
				$large_thumb = '/media/wbty_gallery/thumbs/'.$item->id.'-large'.$ext;
			
				//if (!file_exists(JURI::root(true) . $small-thumb)) $small_thumb = $item->image;
				//if (!file_exists(JURI::root(true) . $large-thumb)) $large_thumb = $item->image;
				
				echo '
						<div class="image">
							<img src="'. JURI::root(true) . $large_thumb . '" />
						</div>
					';
			}
			if ($this->params->get('display_custom_fields') && file_exists(JPATH_BASE . '/components/com_wbty_custom_fields/helpers/loader.php')) {
				require_once(JPATH_BASE . '/components/com_wbty_custom_fields/helpers/loader.php');
				
				echo Wbty_custom_fieldsHelperLoader::loadValues('com_wbty_gallery', 'images', $this->item->id);
			}
			?>
            <?php if ($this->params->get('display_description', 1)) : ?>
            <div class="desc">
            <?php echo $item->content; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="pay-options">
        <?php if ($item->for_sale) : ?>
        	<?php 
				require_once(JPATH_BASE . '/components/com_wbty_pricing/helpers/wbty_pricing.php');
				$price_set = Wbty_pricingHelper::getPricingSet($item->pricing_set);
			?>
            <h4>Purchase this item</h4>
            <form method="post" action="<?php echo JRoute::_('index.php?option=com_wbty_gallery&task=image.add2cart'); ?>" class="form-horizontal">
            	<div class="control-group">
                    <label class="control-label" for="price">Price</label>
                    <div class="controls">
                        <h5>$<?php echo $price_set->base_price; ?></h5>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="quantity">Quantity</label>
                    <div class="controls">
                        <input type="text" id="quantity" name="jform[quantity]" value="1" />
                    </div>
                </div>
                <?php if ($price_set->options) : ?>
                <?php var_dump($price_set->options); ?>
                <div class="control-group">
                    <label class="control-label" for="product">Options</label>
                    <div class="controls">
                        <select id="product" name="jform[product][]">
                        <?php foreach ($price_set->prices as $option) : ?>
                        	<option value="<?php echo $option->id; ?>"><?php echo $option->option_name; if ($option->price) { echo ", +$".$option->price; } ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
                <div class="control-group">
                    <div class="controls">
                        <input type="hidden" id="image" name="jform[image]" value="<?php echo $item->id; ?>" />
                        <input type="submit" value="Add to Cart" />
                    </div>
                </div>
            </form>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>