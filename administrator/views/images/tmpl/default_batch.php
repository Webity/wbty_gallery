<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$options = array(
	JHtml::_('select.option', 'c', JText::_('JLIB_HTML_BATCH_COPY')),
	JHtml::_('select.option', 'm', JText::_('JLIB_HTML_BATCH_MOVE'))
);
$published	= $this->state->get('filter.published');
?>
<fieldset class="batch">
	<legend><?php echo JText::_('Batch Changes');?></legend>
	<?php //echo JHtml::_('batch.access');?>
	<?php //echo JHtml::_('batch.language'); ?>

	<?php if ($published >= 0) : ?>
		<label for="batch-category">
			<?php echo JText::_('Change Category'); ?>
		</label>
		<select name="batch[category]" id="batch-category">
			<option value=""><?php echo JText::_('JSELECT') ?></option>
			<?php echo JHtml::_('select.options', JHtml::_('category.categories', 'com_wbty_gallery', array('filter.published' => $published)));?>
		</select>
	<?php endif; ?>
    
    <?php if ($this->params->get('pricing')) : ?>
		<label style="clear:left;">
			<?php echo JText::_('Set For Sale'); ?>
		</label>
        <fieldset class="combo" style="clear:left">
		<input type="radio" name="batch[for_sale]" value="" id="ignore_for_sale" checked="checked" /><label for="ignore_for_sale"> Don't Change </label>
		<input type="radio" name="batch[for_sale]" value="0" id="not_for_sale" /><label for="not_for_sale"> Set as Not For Sale </label>
		<input type="radio" name="batch[for_sale]" value="1" id="for_sale" /><label for="for_sale"> Set as For Sale </label>
        </fieldset>
        
        <label>
			<?php echo JText::_('Set Pricing Set'); ?>
		</label>
		<select name="batch[pricing_set]" class="inputbox" id="batch-pricing-id">
			<option value=""><?php echo JText::_('JSELECT') ?></option>
            <?php
			$db = JFactory::getDbo();
			$results = $db->setQuery('SELECT id, name FROM #__wbty_pricing_pricing_sets WHERE state=1 ORDER BY ordering')->loadObjectList();
			
			foreach($results as $r) {
				echo '<option value="'.$r->id.'">'.$r->name.'</option>';
			}
			?>
		</select>
	<?php endif; ?>
	<div class="clr"></div>
	<button type="submit" onclick="submitbutton('image.batch');">
		<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
	</button>
	<button type="button" onclick="document.id('batch-category').value='';document.id('ignore_for_sale').checked = true;document.id('batch-pricing-id').value=''">
		<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
	</button>
</fieldset>
