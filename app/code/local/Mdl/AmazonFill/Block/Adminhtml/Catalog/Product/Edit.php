<?php
/**
 * @category    Mdl
 * @package     Mdl_AmazonFill
 * @copyright   Copyright (c) 2012 Nicolas RIOU (http://www.nicolas-riou.fr)
 */

class Mdl_AmazonFill_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Catalog_Product_Edit
{

    protected function  _prepareLayout()    {
        // Create block for custom amazonfill button
        $this->setChild('fillFromAmazon',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('amazonfill')->__('Fill From Amazon'),
                    'onclick'   => 'amazonfill(\''.$this->getProduct()->getSku().'\')'
                ))
        );

        return parent::_prepareLayout();
    }

}