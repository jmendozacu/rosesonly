<?php

class MDN_AdvancedStock_Block_System_Config_Button_UpdateIsValidForAllOrders extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $url = $this->getUrl('AdvancedStock/Misc/UpdateIsValidForAllOrders');
        
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Update now')
                    ->setOnClick("setLocation('$url')")
                    ->toHtml();

        return $html;
    }
}