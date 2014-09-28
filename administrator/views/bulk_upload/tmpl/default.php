<?php
/**
 * @version $Id: fsslider.php 2011-01-25 12:41:40 svn $
 * @package    Fsslider
 * @subpackage Base
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     David Fritsch {@link fritschservices.com}
 * @author     Created on 27-May-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') or die('=;)');

JHtml::_('behavior.formvalidation');

$doc =& JFactory::getDocument();
$script = "
jQuery(document).ready( function ($) {
	$('#jform_slide_type').change(function () {
		slide_type = $('#jform_slide_type').val();
		$('.subset').hide();
		if (slide_type=='1') {
			$('#image_fieldset').show();
		} else if (slide_type=='2') {
			$('#content_fieldset').show();
		} 
	});

	var t = setInterval('checkPreview()', 500);
});

var image_url;

function checkPreview() {
	if (jQuery('#jform_image').val() != image_url && jQuery('#jform_image').val() != '') {
		image_url = jQuery('#jform_image').val();
		updatePreview();
	}
}

function updatePreview() {
	base_url = '". JURI::root() ."';
	image_url = jQuery('#jform_image').val();
	jQuery('#image-preview').html('<img src=\"' + base_url + image_url + '\" height=\"120px\" />');
}
";

$script .= "

function addCategory() {
	var categoryHTML = jQuery('#category-select').html();
	jQuery.ajax({  
	  type: \"POST\",  
	  url: \"".JRoute::_('index.php?option=com_wbty_gallery&task=bulk_upload.getCategoryFields', false)."\",  
	  data: {'id' : 'category'},  
	  success: function(applyData) {
			jQuery('#category-select').append('<img src=\"".JURI::root(true)."/media/wbty_components/img/load.gif\" id=\"loading-image\">');
			jQuery('#category-select').html(applyData);
			jQuery('#loading-image').remove();
		}
	})
};

function wbtyGallerySetupForm(id, filename, responseJSON) {
	if (responseJSON['filename'] !== undefined) {
		filename = responseJSON['filename'].length > 4 ? responseJSON['filename'] + '.' + responseJSON['ext'] : filename;
	}
	
	jQuery.ajax({  
	  type: \"POST\",  
	  url: \"".JRoute::_('index.php?option=com_wbty_gallery&task=bulk_upload.extraFields', false)."\",  
	  data: {'id' : id, 'filename' : filename},  
	  success: function(applyData) {
			jQuery('#file-forms').append('<img src=\"".JURI::root(true)."/media/wbty_components/img/load.gif\" id=\"loading-image\">');
			jQuery('#file-forms').append(applyData);
			jQuery('#loading-image').remove();
			jQuery('#bulkfiles_' + id + '__image_image').val('images/wbty_gallery/' + filename);
			jQuery('#bulkfiles_' + id + '__image_name').val(filename);
		}
	})
};

function setCategory() {
	category_id = jQuery('#jform_category').val();
	jQuery('.category').val(category_id);
}
";

$doc->addScriptDeclaration($script);
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'bulk_upload.cancel' || document.formvalidator.isValid(document.id('wbty_gallery-form'))) {
			if (document.getElementById('jform_category')) {
				setCategory();
			}
			Joomla.submitform(task, document.getElementById('wbty_gallery-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_gallery&layout=edit&bulk=true', false); ?>" method='post' name='adminForm' id='wbty_gallery-form' class='form-validate'>
<fieldset class="adminform">
<div id="category-select">
    <div class="adminform">
        <div class="control-group">
        	<?php echo $this->form->getLabel('category'); ?>
        	<?php echo $this->form->getInput('category'); ?>
        </div>
        
		<?php $params = JComponentHelper::getParams('com_wbty_gallery');
        if ($params->get('pricing')) : ?>
            <li><?php echo $this->form->getLabel('for_sale'); ?>
            <?php echo $this->form->getInput('for_sale'); ?></li>
            <li><?php echo $this->form->getLabel('pricing_set'); ?>
            <?php echo $this->form->getInput('pricing_set'); ?></li>
        <?php endif; ?>
    </div>
</div>
</fieldset>

<?php JHtml::_('wbty.fileuploader', 'index.php?option=com_wbty_gallery&task=bulk_upload.bulkSave', 'wbtyGallerySetupForm'); ?>

<div id="bulk-upload"></div>
<div id="file-forms"></div>

<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>