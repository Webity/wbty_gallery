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

<?php if( $this->item ) : ?>

    <div class="item_fields">
        
        <ul class="fields_list">

        
        
                    <li><?php echo 'id'; ?>: 
                    <?php echo $this->item->id; ?></li>

        
        
                    <li><?php echo 'ordering'; ?>: 
                    <?php echo $this->item->ordering; ?></li>

        
        
                    <li><?php echo 'state'; ?>: 
                    <?php echo $this->item->state; ?></li>

        
        
                    <li><?php echo 'checked_out'; ?>: 
                    <?php echo $this->item->checked_out; ?></li>

        
        
                    <li><?php echo 'checked_out_time'; ?>: 
                    <?php echo $this->item->checked_out_time; ?></li>

        
        
                    <li><?php echo 'data_type'; ?>: 
                    <?php echo $this->item->data_type; ?></li>

        
        
                    <li><?php echo 'min'; ?>: 
                    <?php echo $this->item->min; ?></li>

        
        
                    <li><?php echo 'max'; ?>: 
                    <?php echo $this->item->max; ?></li>

        
        
                    <li><?php echo 'length'; ?>: 
                    <?php echo $this->item->length; ?></li>

        

        </ul>
        
    </div>

<?php endif; ?>