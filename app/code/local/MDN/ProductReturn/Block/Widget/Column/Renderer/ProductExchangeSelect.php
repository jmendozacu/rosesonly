<?php


class MDN_ProductReturn_Block_Widget_Column_Renderer_ProductExchangeSelect
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract 
{
    public function render(Varien_Object $row)
    {
    	$rpId = mage::app()->getRequest()->getParam('rp_id');
    	
    	//init vars
		$name = $row->getname();
		$productId = $row->getId();
		$configurableAttributesDescription = mage::helper('ProductReturn/Configurable')->getDescription($productId);
		if ($configurableAttributesDescription != '')
			$name .= '<i>'.$configurableAttributesDescription.'</i>';

		
    	$name = str_replace("'", " ", $name);
    	$name = str_replace('"', " ", $name);
		
		
		
    	$onclick = 'selectProductForExchange('.$row->getId().', '.$rpId.', \''.$name.'\')';
    	$html = '<a href="#" onclick="'.$onclick.'">'.$this->__('Select').'</a>';
    	return $html;
    }
}
