<?php
/**
 * Created by PhpStorm.
 * User: dvhoang
 * Date: 2/25/14
 * Time: 4:40 PM
 */

class Ant_Advancereports_Block_Adminhtml_Order_Renderer_Color extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $order_id = $row->getData('increment_id');
        $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
        $items = $order->getAllItems();

        $rs = '';
        $color = array();
        foreach ($items as $item) {
            if($item->getProductType()== 'bundle'){
                $s =  explode('-',$item->getSku());
                if(count($s)>1)
                    $color[] = $s[1];
            }
        }

        if (count($color)) {
            $rs = implode(', ', $color);
        }
        return $rs;
    }
}