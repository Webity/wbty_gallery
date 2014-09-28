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
class Wbty_galleryControllerImage extends WbtyControllerForm
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
	
	
	
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Image');

		// Preset the redirect
		$this->setRedirect('index.php?option=com_wbty_gallery&view=images');

		return parent::batch($model);
	}
	
}