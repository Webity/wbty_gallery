<?php
/**
 * @version     1.0.1
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Wbty_gallery helper.
 */
class Wbty_galleryHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		
		
		JSubMenuHelper::addEntry(
			JText::_('COM_WBTY_GALLERY_TITLE_CONTROLPANEL'),
			'index.php?option=com_wbty_gallery&view=controlpanel',
			$vName == 'controlpanel'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_WBTY_GALLERY_TITLE_IMAGES'),
			'index.php?option=com_wbty_gallery&view=images',
			$vName == 'images'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_WBTY_GALLERY_TITLE_CATEGORIES'),
			'index.php?option=com_categories&extension=com_wbty_gallery',
			$vName == 'categories'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_WBTY_GALLERY_TITLE_BULK_UPLOAD'),
			'index.php?option=com_wbty_gallery&view=bulk_upload',
			$vName == 'bulk_upload'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_wbty_gallery';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}

class JHTMLWbty_galleryHelper {
	

	public static function buildEditForm($form, $hidden = true) {
		if (!$form instanceof JForm) {
			return false;
		}

		ob_start();
		foreach ($form->getFieldsets() as $fieldset) {
			echo '<fieldset name="'.$fieldset->name.'"';
			$class = array();
			$field = $value = '';
			if ($fieldset->multiple) {
				 $class[] = 'multiple';
			}
			if ($fieldset->dependency) {
				$class[] = 'dependency';
				$field = $fieldset->field;
				$value = $fieldset->value;
			}
			if ($class) {
				echo ' class="'. implode(' ', $class) . '"';
			}
			if ($field && $value) {
				echo ' data-field="'. $field . '" data-value="'. $value . '"';
			}
			if ($fieldset->copy) {
				echo ' data-copy="' . $fieldset->copy . '"';
			}
			echo '>';
			if ($fieldset->legend) {
				echo '<legend>'.$fieldset->legend.'</legend>';
			}
			if ($fieldset->soc) {
				echo '<p>This section should have a search or create option. Only one is currently shown.</p>';
			}
			//echo '<div class="edit-values">';
			foreach($form->getFieldset($fieldset->name) as $field):
				if (!$field->hidden && $field->display_value) {
				//	echo strip_tags($field->label) . ': <span class="' . str_replace(array('[',']'), array('_'),$field->name) . '">' . $field->value . '</span><br>';
				}
			endforeach;
			echo '<!--</div>-->
			<div class="edit-form">';
			foreach($form->getFieldset($fieldset->name) as $field):
				// If the field is hidden, only use the input.
				if ($field->hidden):
					echo $field->input;
				else:
				?>
				<div class="control-group">
					<?php echo str_replace('<label', '<label class="control-label"', $field->label); ?>
					<div class="controls">
						<?php echo $field->input; ?>
					</div>
				</div>
				<?php
				endif;
			endforeach;
			echo '</div>';
			echo '</fieldset>';
		}
		$html = ob_get_contents();
		ob_end_clean();

		if ($hidden) {
			$html = '<div style="display:none;" id="hidden-forms">'.$html.'</div>';
		}

		return $html;
	}
}
