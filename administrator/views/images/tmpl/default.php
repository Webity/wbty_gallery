<?php
/**
 * @version     1.0.1
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */


// no direct access
defined('_JEXEC') or die;

$jversion = new JVersion();
$above3 = version_compare($jversion->getShortVersion(), '3.0', 'ge');
if (!$above3) {
	JFactory::getDocument()->addScriptDeclaration('
jQuery(document).ready(function($) {
	$("[title]").tooltip();
});
		');
} else {
	JHtml::_('bootstrap.tooltip');
}

JHTML::_('script','system/multiselect.js',false,true);

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_wbty_gallery');
$saveOrder	= $listOrder == 'a.ordering';

$params = JComponentHelper::getParams('com_wbty_gallery');

ob_start();
?>
jQuery(document).ready(function($) {
	$('.state-filter').click(function() {
		if ($(this).hasClass('active')) {return;}

		value = 1;
		if ($(this).hasClass('trashed')) {value = -2;}

		$('#adminForm').append('<input type="hidden" name="filter_published" value="'+ value +'" />').submit();
	});
});
<?php 
$script = ob_get_contents();
ob_end_clean();
JFactory::getDocument()->addScriptDeclaration($script);
?>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_gallery&view=images'); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<div class="state-filters">
		<div class="state-filter published<?php if ($this->state->get('filter.state') != -2) { echo ' active'; } ?>"><?php echo JText::_('JPUBLISHED'); ?></div>
		<div class="state-filter trashed<?php if ($this->state->get('filter.state') == -2) { echo ' active'; } ?>"><?php echo JText::_('JTRASHED'); ?></div>
	</div>
	<div class="clr"></div>
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			<div class="btn-group">
				<button class="btn" type="submit"><i class="icon-search"></i></button>
				<button class="btn" type="button" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>
		<div class="filter-select fltrt">
	        <select name="filter_category" class="inputbox" id="filter_category" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JSELECT');?> Category</option>
               <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_wbty_gallery', array('filter.published' => 1)), 'value', 'text', $this->state->get('filter.category'));?>
            </select>

            <?php if ($params->get('pricing')) : ?>
	        	<select name="filter_forsale" class="inputbox" id="filter_forsale" onchange="this.form.submit()">
	                <option value=""><?php echo JText::_('JSELECT');?> For Sale</option>
	               <?php echo JHtml::_('select.options', array(array('value'=>1, 'text'=>'Not For Sale'), array('value'=>2, 'text'=>'For Sale')), 'value', 'text', $this->state->get('filter.forsale'));?>
	            </select>
	        <?php endif; ?>
		</div>
	</fieldset>
	<div class="clr"></div>

	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
                <th></th>
                
                
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_GALLERY_FORM_LBL_IMAGES_NAME', '.name', $listDirn, $listOrder); ?>
					</th>
					
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_GALLERY_FORM_LBL_IMAGES_IMAGE', '.image', $listDirn, $listOrder); ?>
					</th>
					
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_GALLERY_FORM_LBL_IMAGES_CAPTION', '.caption', $listDirn, $listOrder); ?>
					</th>
					
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_GALLERY_FORM_LBL_IMAGES_CATEGORY', 'category_title', $listDirn, $listOrder); ?>
					</th>
					
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_GALLERY_FORM_LBL_IMAGES_MENU_LINK', '.menu_link', $listDirn, $listOrder); ?>
					</th>

					<?php if ($params->get('pricing')) : ?>
						<th>
							<?php echo JHtml::_('grid.sort',  'COM_WBTY_GALLERY_FORM_LBL_IMAGES_FOR_SALE', '.for_sale', $listDirn, $listOrder); ?>
						</th>
						
						<th>
							<?php echo JHtml::_('grid.sort',  'COM_WBTY_GALLERY_FORM_LBL_IMAGES_PRICING_SET', '.pricing_set', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					


                <?php if (isset($this->items[0]->state)) { ?>
				<th width="5%">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
				</th>
                <?php } ?>
                <?php if (isset($this->items[0]->ordering)) { ?>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'images.saveorder'); ?>
					<?php endif; ?>
				</th>
                <?php } ?>
                <?php if (isset($this->items[0]->id)) { ?>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
                <?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_wbty_gallery');
			$canEdit	= $user->authorise('core.edit',			'com_wbty_gallery');
			$canCheckin	= $user->authorise('core.manage',		'com_wbty_gallery');
			$canChange	= $user->authorise('core.edit.state',	'com_wbty_gallery');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
                <td class="center">
                	<div class="btn-group">
                      <span class="btn dropdown-toggle" data-toggle="dropdown">Actions</span>
                      <span class="btn dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>&nbsp;
                      </span>
                      <ul class="dropdown-menu">
                        <?php
                        echo '<li><a href="index.php?option=com_wbty_gallery&view=image&layout=default&id='.$item->id.'">View</a></li>';
						if ($canEdit && $this->state->get('filter.state') != -2) {
	                        echo '<li><a href="index.php?option=com_wbty_gallery&task=image.edit&id='.$item->id.'">Edit</a></li>';
						}
                        ?>
                        
                      </ul>
                    </div>
                </td>
                
                
						<td>
							<?php if (isset($item->checked_out) && $item->checked_out && (JDate::getInstance()->toUnix() - JDate::getInstance($item->checked_out_time)->toUnix()) < 120 ) : ?>

								<span class="hasTip" title="Item is currently being edited by Super User"><i class="icon-lock"></i></span>
							<?php endif; ?>
							<?php echo $this->escape($item->name); ?>
						</td>
						
						<td>
							<?php 
							$ext = strtolower(strrchr($item->image, '.'));
							$small_thumb = '/media/wbty_gallery/thumbs/'.$item->id.'-small'.$ext;
							if (file_exists(JPATH_ROOT . $small_thumb)) : 
							?>
								<img src="<?php echo JURI::root(true) . $small_thumb; ?>"width="200" />
							<?php else : ?>
								Edit Image
							<?php endif; ?>
						</td>
						
						<td>
							<?php echo $item->caption; ?>
						</td>
						
						<td>
							<?php echo $item->category_title; ?>
						</td>
						
						<td>
							<?php echo $item->menu_link; ?>
						</td>
						
						<?php if ($params->get('pricing')) : ?>
							<td>
								<?php echo ($item->for_sale ? 'Yes' : 'No'); ?>
							</td>
							
							<td>
								<?php echo $item->pricing_set; ?>
							</td>
						<?php endif; ?>
						


                <?php if (isset($this->items[0]->state)) { ?>
				    <td class="center">
					    <?php echo JHtml::_('jgrid.published', $item->state, $i, 'images.', $canChange, 'cb'); ?>
				    </td>
                <?php } ?>
                <?php if (isset($this->items[0]->ordering)) { ?>
				    <td class="order">
					    <?php if ($canChange) : ?>
						    <?php if ($saveOrder) :?>
							    <?php if ($listDirn == 'asc') : ?>
                                    <span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->category == $item->category), 'images.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->category == $item->category), 'images.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                <?php elseif ($listDirn == 'desc') : ?>
                                    <span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->category == $item->category), 'images.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->category == $item->category), 'images.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                <?php endif; ?>
						    <?php endif; ?>
						    <?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						    <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					    <?php else : ?>
						    <?php echo $item->ordering; ?>
					    <?php endif; ?>
				    </td>
                <?php } ?>
                <?php if (isset($this->items[0]->id)) { ?>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
                <?php } ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="pagination pagination-toolbar">
		<?php echo $this->pagination->getListFooter(); ?>
	</div>
	<div>
	    
	    
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>