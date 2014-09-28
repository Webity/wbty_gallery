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

jimport('wbty_components.controllers.wbtycontrollerform');

/**
 * image controller class.
 */
class Wbty_galleryControllerBulk_upload extends WbtyControllerForm
{

    function __construct() {
        $this->view_list = 'images';
        parent::__construct();
		
		$this->_model = $this->getModel();
    }
	
	function back() {
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_list
				. $this->getRedirectToListAppend(), false
			)
		);
	}
	
	function ajax_save() {
		$this->model = $this->getModel();
		$jinput = JFactory::getApplication()->input;
		$jform = $jinput->get('jform', array(), 'ARRAY');
		$data = $jform['image'];

		$return = array(json_encode($data));
		if (JSession::checkToken() && $id = $this->model->save($data, array())) {
			require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/ajax.php');
			$helper = new wbty_galleryHelperAjax;
			$form = $this->model->getForm(array(), true, 'jform', $id);

			$return['id'] = $id;
			$return['data'] = $helper->link_html('image', $id, $form);
			$return['token'] = JSession::getFormToken();
		} else {
			$return['error'] = "error";
			$return['token'] = JSession::getFormToken();
		}
		echo json_encode($return);
		exit();
	}

	function ajax_checkout() {
		$app = JFactory::getApplication();
		
		if (!$id = $app->input->get('id', 0)) {
			echo json_encode(array('error'=>'No id set'));
			exit();
		}

		$this->model = $this->getModel();
		$table = $this->model->getTable();

		$table->load($id);
		$checkout = $table->checkout(JFactory::getUser()->id);

		$return = array();
		if ($table->id == $id && $checkout) {
			$return['id'] = $id;
			$return['token'] = JSession::getFormToken();
		} else {
			$return['error'] = "Unable to load or checkout record";
			$return['token'] = JSession::getFormToken();
		}

		echo json_encode($return);
		exit();
	}

	function ajax_state() {
		$app = JFactory::getApplication();

		if (!$id = $app->input->get('id', 0)) {
			echo json_encode(array('error'=>'ID set incorrectly'));
			exit();
		}
		
		$state = $app->input->get('state_val', 0);
		if (!($state == 1 || $state == -2)) {
			echo json_encode(array('error'=>'Invalid state setting' . $state));
			exit();
		}

		$this->model = $this->getModel();
		$status = $this->model->publish($id, $state);

		$return = array();
		if ($status) {
			$return['id'] = $id;
			$return['token'] = JSession::getFormToken();
			$return['state'] = $state;
		} else {
			$return['error'] = "Unable to update state of item";
			$return['token'] = JSession::getFormToken();
		}

		echo json_encode($return);
		exit();
	}

	function ajax_order() {
		$app = JFactory::getApplication();
		if (!$ids = $app->input->get('ids', array(), 'ARRAY')) {
			echo json_encode(array('error'=>'IDs set incorrectly'));
			exit();
		}

		$this->model = $this->getModel();
		$status = $this->model->setOrder($ids);

		$return = array();
		if ($status) {
			$return['success'] = true;
		} else {
			$return['error'] = "Unable to reorder items";
		}

		echo json_encode($return);
		exit();
	}

	
	function getLinkTableName($name) {
		require_once (JPATH_COMPONENT. '/models/'.$name.'.php' );
		$model = JModel::getInstance( $name, 'Wbty_galleryModel' );
		$table = $model->getTable();
		return $table->getTableName();
	}
	
	function add() {		
		$view = & $this->getView( 'file', 'html' );
		$model = & $this->getModel('file');
		
		$view->setModel($model, true);
		$view->setLayout('bulk');
		
		$view->display();
	}
	
	/**
     * Preapres a form element for a new category
     */
	function getCategoryFields() {
		
		$model = $this->getModel();
		$category_id = JRequest::getVar('id');
		$form = $model->getFieldForm($category_id, 'category');
		
		if (!$form) {
			echo "There was an error creating the form";
			exit();
		}
		
		echo '<div class="fltlft" style="width:250px;">';
		echo '<fieldset class="adminform" >';
		echo '<legend>New Category</legend>';
		echo '<div class="adminformlist">';
		foreach ($form->getFieldset() as $field) {
			JHtml::_('wbty.renderField', $field);
		}
		echo "</div></fieldset></div>";
		
		exit();
	}
	
	/**
     * Preapres a form element for a file upload
     */
	function extraFields() {
	
		$file_id = JRequest::getVar('id');
		$filename = JRequest::getVar('filename');
		
		if (!$filename) {
			exit();
		}
		
		$model = $this->getModel();
		$form = $model->getFieldForm($file_id, 'bulk');
		
		if (!$form) {
			echo "There was an error creating the form";
			exit();
		}
		
		echo '<div class="fltlft" style="width:250px;">';
		echo '<fieldset class="adminform">';
		echo '<legend>Edit '.$filename.'</legend>';
		echo '<div class="adminformlist">';

		foreach ($form->getFieldset() as $field) {
			JHtml::_('wbty.renderField', $field);
		}
		echo "</div></fieldset></div>";
		
		exit();
	}
	
	/**
     * Calls the fileuploader.php helper file and sets the basic parameters
	 * @return	array	Returns success or and error message
     */
	function bulkSave() {
		
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array();
		// max file size in bytes
		$sizeLimit = 10 * 1024 * 1024;
		
		$uploader = new WbtyFileUploader($allowedExtensions, $sizeLimit);

		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		if (!JFolder::exists(JPATH_ROOT.'/images/wbty_gallery/')) {
			JFolder::create(JPATH_ROOT.'/images/wbty_gallery/');
		}
		$directory = JPATH_ROOT.'/images/wbty_gallery/';
		$result = $uploader->handleUpload($directory);
		
		echo json_encode($result);exit();
	}
	
	function link () {
		$link_name = JRequest::getVar('link');
		$id = JRequest::getVar('id');
		$key = JRequest::getVar('key');
		
		$table_name = $this->getLinkTableName($link_name);
		
		$this->link = $this->_model->getLinkedTable($id, $link_name, $link_name, $table_name, false, "{".$link_name."}", '');
		echo $this->link_html($link_name, '{' . $link_name . '}', $this->link["{".$link_name."}"]);
		exit();
	}
	function link_html($link_name ='', $key = '{user_groups}', $link) {
		if (!$link_name) {
			return '';
		}
		$string .= '<li id="'.$link_name.$key.'"><fieldset><legend>Group: </legend><ul>';
		foreach ($link['form']->getFieldset('fields') as $field) {
			$string .= '<li>'.$field->__get('label').$field->__get('input').'</li>';
		}
		$string .= '</ul><a href="#'.$key.'" class="'.$link_name.'remove">Remove item</a></fieldset></li>';
		
		return $string;
	}
	
	function link_load() {
		$link_name = JRequest::getVar('link');
		$id = JRequest::getVar('id');
		
		$table_name = $this->getLinkTableName($link_name);
		
		$this->link = $this->_model->getLinkedTable($id, $link_name, $link_name, $table_name, true, 0, '');
		
		if (is_array($this->link)) {
			foreach ($this->link as $key=>$link) {
				echo $this->link_html($link_name, $key, $link);
			}
		}
		echo '<a href="#" id="'.$link_name.'add">Add</a>';
		exit();
	}
	
	function save($key = null, $urlVar = null) {
		$jinput = JFactory::getApplication()->input;

		if ($jinput->get('bulk')) {

			// process forms here
			$db =& JFactory::getDBO();
			$task = $this->getTask();
			$app   = JFactory::getApplication();
			$model = $this->getModel('image');
			$form = $model->getForm();
			$fieldsets  = JRequest::getVar('bulkfiles', array(), 'post', 'array');
			
			$mainitems = JRequest::getVar('jform');
			
			foreach($fieldsets as $key => $value) {
				
				$jform['image'] = $mainitems;
				foreach ($value['image'] as $k=>$v) {
					$jform['image'][$k] = $v;
				}

				// Access check.
				if (!$this->allowSave($jform, $key)) {
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
					$this->setMessage($this->getError(), 'error');
		
					$this->setRedirect(
						JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_list
							. $this->getRedirectToListAppend(), false
						)
					);

					return false;
				}
				
				// Test whether the data is valid.
				$validData = $model->validate($form, $jform);
var_dump($form);
var_dump($validData);
				if ($validData === false) {
					
					// Get the validation messages.
					$errors = $model->getErrors();
		
					// Push up to three validation messages out to the user.
					for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
					{
						if ($errors[$i] instanceof Exception)
						{
							$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
						}
						else
						{
							$app->enqueueMessage($errors[$i], 'warning');
						}
					}
		var_dump($context);
		var_dump($jform);
					// Save the data in the session.
					$app->setUserState($context . '.data', $jform);
		
					// Redirect back to the edit screen.
					$this->setRedirect(
						JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_item
							. $this->getRedirectToItemAppend($recordId, $key), false
						)
					);
		
					return false;
				}
				
				// Attempt to save the data.
				if (!$model->save($validData['image']))
				{
					// Save the data in the session.
					//$app->setUserState($context . '.data', $jform);
		
					// Redirect back to the edit screen.
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
					$this->setMessage($this->getError(), 'error');
		
				} else {
					$app->enqueueMessage('Success');
				}
				
				// Redirect the user and adjust session state based on the chosen task.
				switch ($task)
				{
					case 'apply':	
					case 'save2new':
					default:

						// Redirect to the list screen.
						$this->setRedirect(
							JRoute::_(
								'index.php?option=' . $this->option . '&view=' . $this->view_list . '&category_id=' . $jform['category_id']
								. $this->getRedirectToListAppend(), false
							)
						);
						break;
				}
			}
			
		} else {
			return parent::save($key = null, $urlVar = null);
		}
	}
	
}