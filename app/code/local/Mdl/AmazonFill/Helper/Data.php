<?php
/**
 * @category    Mdl
 * @package     Mdl_AmazonFill
 * @copyright   Copyright (c) 2012 Nicolas RIOU (http://www.nicolas-riou.fr)
 */
 
class Mdl_AmazonFill_Helper_Data extends Mage_Core_Helper_Abstract{

    public function getAmazonProductContent($sku)
    {
    	return @file_get_contents('http://www.amazon.fr/gp/product/'.$sku.'/ref=olp_product_details?ie=UTF8&me=&seller=');
    }

    public function getAmazonOfferListingContent($sku)
    {
    	return @file_get_contents('http://www.amazon.fr/gp/offer-listing/'.$sku.'/ref=dp_olp_new?ie=UTF8&condition=new');
    }
	
    public function getName($productContent)    {
    	$title = NULL;
    	if(preg_match_all('#\\<span id=\\"btAsinTitle\\"\\>(.+)\\<\\/span\\>#',$productContent,$matches)) {
    		$title = $matches[1][0];
		}
		return $title;
    }

    public function getDescription($productContent)    {
    	$description = NULL;
        if(preg_match_all('#\\<div class=\\"productDescriptionWrapper\\"\\>(.+)\\<div id=\\"purchase-sims-feature\\"\\>#',$productContent,$matches)) {
    		$description = $matches;
		}
		return $description;
    }

    public function getActualPrice($sku)    {
		return Mage::getModel('catalog/product')->loadByAttribute('sku', $sku)->getPrice();
    }

    public function getPrices($offerListingContent)    {
    	$prices = NULL;
    	if(preg_match_all('#\\<span class=\\"price\\"\\>EUR (.+)\\<\\/span\\>#',$offerListingContent,$matches)) {
    		$prices = $matches[1];
		}
		return $prices;
    }

    public function comparePrices($actualPrice, $prices)    {
    	$actualPrice = str_replace(".", ",", $actualPrice);
		foreach ($prices as $price) {
	    	$diff[$price] = (int) abs($actualPrice - $price);
	    }
		$prices = array_flip($diff);
		return $prices[min($diff)];
    }

    public function lowPrice($price,$low)    {
    	$price = str_replace(",", ".", $price);
    	$price = $price - $low;
    	$price = str_replace(".", ",", $price);
		return $price;
    }

}