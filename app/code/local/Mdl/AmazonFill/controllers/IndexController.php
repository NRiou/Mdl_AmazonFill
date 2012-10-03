<?php
/**
 * @category    Mdl
 * @package     Mdl_AmazonFill
 * @copyright   Copyright (c) 2012 Nicolas RIOU (http://www.nicolas-riou.fr)
 */

class Mdl_AmazonFill_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction() {
    	ini_set('max_execution_time', 5000);
    	$error = FALSE;
    	$name = NULL;
    	$description = NULL;
    	$price = NULL;
    	$sku = $this->getRequest()->getParam('sku');
    	if ($sku) {
    		// Get pages content
    		$productContent = Mage::helper('amazonfill')->getAmazonProductContent($sku);
    		$offerListingContent = Mage::helper('amazonfill')->getAmazonOfferListingContent($sku);
    		if ($productContent && $offerListingContent) {
				// Retrieve prices from offer listing page
				$prices = Mage::helper('amazonfill')->getPrices($offerListingContent);
	    		// Retrieve from product page
	    		$name = Mage::helper('amazonfill')->getName($productContent);
	    		$name = mb_check_encoding($name, 'UTF-8') ? $name : utf8_encode($name);
	    		// TODO Description
	    		//$description = Mage::helper('amazonfill')->getDescription($productContent);
	    		$actualPrice = Mage::helper('amazonfill')->getActualPrice($sku);
	    		// Adjust price depending amazon sellers prices
	    		$closestPrice = Mage::helper('amazonfill')->comparePrices($actualPrice, $prices);
				$price = Mage::helper('amazonfill')->lowPrice($closestPrice,0.20);
    		}
    		else {
    			$error = 'Unable to get contents from amazon';
    		}
    	}
    	else {
    		$error = 'SKU field is required';
    	}
		$this->getResponse()
			 ->setHeader('Content-Type','application/json',true)
			 ->setBody(
	    		json_encode(
	    			array(
	    				'error' => $error,
		    			'name' => $name,
		    			//'description' => $description
		    			'price' => $price
		    		)
		    	)
			 );
    }
}