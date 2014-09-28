<?php
/**
 * @version     1.0.0
 * @package     com_wbty_gallery
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * image controller class.
 */
class Wbty_galleryControllerimage extends JControllerLegacy
{

    function __construct() {
        $this->view_list = 'images';
        parent::__construct();
    }
	
	function add2cart() {
		$jform = JFactory::getApplication()->input->get('jform');
		$app =& JFactory::getApplication();
		
		$url = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : 'index.php?option=com_wbty_gallery';
		
		jimport('wbtypayments.wbtypayments');
		
		if (!class_exists('WbtyPayments')) {
			$app->enqueueMessage('Add to cart requires Webity Payments. Please let the webmaster know that Webity Payments is not found.');
			$app->redirect($url);
		}
		
		$image_model = $this->getModel('image');
		$image_model->getState();
		$image_model->setState('image.id', $jform['image']);
		$image = $image_model->getItem();
		
		require_once(JPATH_BASE . '/components/com_wbty_pricing/helpers/wbty_pricing.php');
		$price_set = Wbty_pricingHelper::getPricingSet($image->pricing_set);
		
		$price = $price_set->base_price;
		$description = $price_set->name;
		foreach ($jform['product'] as $option=>$item) {
			foreach($price_set->options[$option] as $opt) {
				if ($opt->id == $item) {
					$price += $opt->price_change;
					$description .= ', ' . $opt->title;
					break;
				}
			}
		}
		
		$ext = strtolower(strrchr($image->image, '.'));
		$small_thumb = '/media/wbty_gallery/thumbs/'.$image->id.'-small'.$ext;
				
		$order_info = array(
						'amount' => $price,
						'item_name' => $image->name,
						'item_desc' => $description,
						'item_id' => $image->id,
						'item_image' => $small_thumb,
						'callback_file' => '',
						'callback_function' => '',
						'callback_id' => 0,
						'redirect_url' => '',
						'redirect_text' => ''
						);
		
		if (WbtyPayments::createOrder($order_info)) {
			$url = WbtyPayments::getCheckoutUrl();
		} else {
			$app->enqueueMessage('Error Purchasing Image...');
		}
		
		$app->redirect($url);
		exit();
	}

}