<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_SeoSuite
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * SEO Suite extension
 *
 * @category   MageWorx
 * @package    MageWorx_SeoSuite
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */

class MageWorx_SeoSuite_Block_Catalog_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{

    public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return Mage::helper('seosuite')->getLayerFilterUrl($urlParams);
    }
    
    public function getAvailableOrders() {
        $url = explode('/',$_SERVER['REQUEST_URI']);
        $customisepage = false;
        foreach($url as $params){
            if($params == 'customisation-page' || $params=='customisation-page.html'){
                $customisepage = true;
            }
        }
        $this->_availableOrder = array();
        if($customisepage == true){
            $this->addOrderToAvailableOrders('position', 'Position');
        }else
        {
            $this->addOrderToAvailableOrders('created_at', 'Sort by');
            $this->addOrderToAvailableOrders('created_at', 'New Arrivals');
            $this->addOrderToAvailableOrders('sku', 'SKU');
            $this->addOrderToAvailableOrders('price', 'Price');
            $this->setDefaultOrder('sku');
        }

        $this->setDefaultDirection('asc');
        return $this->_availableOrder;
    }

}
