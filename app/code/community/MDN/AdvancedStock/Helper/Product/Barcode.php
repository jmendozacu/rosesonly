<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2009 Maison du Logiciel (http://www.maisondulogiciel.com)
 * @author : Olivier ZIMMERMANN
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MDN_AdvancedStock_Helper_Product_Barcode extends Mage_Core_Helper_Abstract {

    /**
     * Define barcode mode
     * @return <type>
     */
    public function useStandardErpBarcodeManagement() {
        if ($this->getBarcodeAttribute())
            return false;
        else
            return true;
    }

    /**
     * Return barcode attribute
     * @return <type>
     */
    public function getBarcodeAttribute() {
        return Mage::getStoreConfig('advancedstock/barcode/barcode_attribute');
    }

    /**
     * Return barcodes list for 1 product
     *
     * @param unknown_type $productId
     */
    public function getBarcodesForProduct($productId) {

        if ($this->useStandardErpBarcodeManagement()) {
            $collection = mage::getModel('AdvancedStock/ProductBarcode')
                            ->getCollection()
                            ->addFieldToFilter('ppb_product_id', $productId);
        } else {
            $barcode = $this->getBarcodeForProduct($productId);
            $obj = new Varien_Object();
            $obj->setppb_barcode($barcode);
            $collection = array($obj);  //return a collection for backward compatibility
        }

        return $collection;
    }

    public function saveBarcodesFromString($productId, $string) {

        //save barcode in erp tables
        if ($this->useStandardErpBarcodeManagement()) {
            $t = explode("\r\n", $string);

            $collection = $this->getBarcodesForProduct($productId);
            foreach ($collection as $item) {
                //check if we have to delete a barcode
                if (!in_array($item->getppb_barcode(), $t))
                    $item->delete();
                else
                    $t = array_diff($t, array($item->getppb_barcode()));
            }

            //barcodes that still in $t have to be inserted
            $hadBarcodes = (count($t) > 0);
            foreach ($t as $barcode) {
                if ($barcode == '')
                    continue;

                mage::getModel('AdvancedStock/ProductBarcode')
                        ->setppb_product_id($productId)
                        ->setppb_barcode($barcode)
                        ->setppb_is_main($hadBarcodes)
                        ->save();

                $hadBarcodes = true;
            }
        }
        else {
            //if we have to save barcode in attribute
            Mage::getSingleton('catalog/product_action')
                    ->updateAttributes(array($productId), array($this->getBarcodeAttribute() => $string), 0);
        }
    }

    /**
     * Add a barcode if doesn't exists
     *
     * @param unknown_type $productId
     * @param unknown_type $barcode
     */
    public function addBarcodeIfNotExists($productId, $barcode) {
        if (!$this->barcodeExists($barcode)) {
            if ($this->useStandardErpBarcodeManagement()) {
                mage::getModel('AdvancedStock/ProductBarcode')
                        ->setppb_product_id($productId)
                        ->setppb_barcode($barcode)
                        ->setppb_is_main(1)
                        ->save();
                return true;
            } else {
                $this->saveBarcodesFromString($productId, $barcode);
            }
        }
        return false;
    }

    /**
     * Check if a barcode exists
     *
     * @param unknown_type $barcode
     */
    public function barcodeExists($barcode) {
        if ($this->useStandardErpBarcodeManagement()) {
            $collection = mage::getModel('AdvancedStock/ProductBarcode')
                            ->getCollection()
                            ->addFieldToFilter('ppb_barcode', $barcode);
            return ($collection->getSize() > 0);
        } else {
            $collection = Mage::getModel('catalog/product')
                            ->getCollection()
                            ->addAttributeToFilter($this->getBarcodeAttribute(), $barcode);
            return ($collection->getSize() > 0);
        }
    }

    /**
     * Return a product from a barcode
     *
     * @param unknown_type $barcode
     */
    public function getProductFromBarcode($barcode) {
        $product = null;

        if ($this->useStandardErpBarcodeManagement()) {
            $object = mage::getModel('AdvancedStock/ProductBarcode')->load($barcode, 'ppb_barcode');
            if ($object->getId())
                $product = mage::getModel('catalog/product')->load($object->getppb_product_id());
        }
        else {
            $product = Mage::getModel('catalog/product')
                            ->getCollection()
                            ->addAttributeToFilter($this->getBarcodeAttribute(), $barcode)
                            ->getFirstItem();
            if (!$product->getId())
                $product = null;
        }

        return $product;
    }

    /**
     * Return main barcode for product
     *
     */
    public function getBarcodeForProduct($product) {
        $productId = null;
        if (is_object($product))
            $productId = $product->getId();
        else
            $productId = $product;
        $retour = null;

        if ($this->useStandardErpBarcodeManagement()) {
            //get barcode from erp table
            $barcodes = mage::getModel('AdvancedStock/ProductBarcode')
                            ->getCollection()
                            ->addFieldToFilter('ppb_product_id', $productId)
                            ->addFieldToFilter('ppb_is_main', 1);
            foreach ($barcodes as $item)
                $retour = $item->getppb_barcode();
        } else {
            //Get barcode from attribute
            //todo : find a way to not load product !!!!
            $barcodeAttribute = Mage::getStoreConfig('advancedstock/barcode/barcode_attribute');
            $product = Mage::getModel('catalog/product')->load($productId);
            $retour = $product->getData($barcodeAttribute);
        }

        return $retour;
    }

    /**
     * Return barcode picture
     *
     * @param unknown_type $barcode
     */
    public function getBarcodePicture($barcode) {

        $barcodeOptions = array('text' => $barcode);
        $rendererOptions = array();
        $factory = Zend_Barcode::factory(
                        'Code128', 'image', $barcodeOptions, $rendererOptions
        );
        $image = $factory->draw();
        return $image;
    }

    /**
     * Return main barcode picture for product
     *
     * @param unknown_type $product
     * @return unknown
     */
    public function getBarcodePictureForProduct($product) {
        $barcode = $this->getBarcodeForProduct($product);
        return $this->getBarcodePicture($barcode);
    }

}