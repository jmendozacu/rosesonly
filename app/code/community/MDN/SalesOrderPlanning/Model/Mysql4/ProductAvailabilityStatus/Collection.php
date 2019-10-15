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
/**
 * Collection de quotation
 *
 */
class MDN_SalesOrderPlanning_Model_Mysql4_ProductAvailabilityStatus_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('SalesOrderPlanning/ProductAvailabilityStatus');
    }
    
    /**
     * Return product ids having product availability status
     *
     */
    public function getProductsWithStatus($groupCode = null)
    {
		$sql = $this->getSelect()
			->reset()
			->from($this->getMainTable(), 'pa_product_id')
			;
				
		$value = $this->getConnection()->fetchCol($sql);
		return $value;
    }

}