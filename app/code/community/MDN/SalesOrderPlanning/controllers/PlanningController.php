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
class MDN_SalesOrderPlanning_PlanningController extends Mage_Adminhtml_Controller_Action
{
	
	/**
	 * Save planning
	 *
	 */
	public function SaveAction()
	{
		//retrieves object
		$planningId = $this->getRequest()->getPost('psop_id');
		$orderId = $this->getRequest()->getPost('psop_order_id');
		$order = mage::getModel('sales/order')->load($orderId);
		$planning = mage::getModel('SalesOrderPlanning/Planning')->load($planningId);
		
		//defines changes
		$considerationForceDateChange = false;
		$fullstockForceDateChange = false;
		$shippingForceDateChange = false;
		
		//store date to force
		if ($this->getRequest()->getPost('psop_consideration_date_force') != '')
		{
			$considerationForceDateChange = ($this->getRequest()->getPost('psop_consideration_date_force') != $planning->setpsop_consideration_date_force);
			$planning->setpsop_consideration_date_force($this->getRequest()->getPost('psop_consideration_date_force'));
		}
		else 
			$planning->setpsop_consideration_date_force(null);
			
		if ($this->getRequest()->getPost('psop_fullstock_date_force') != '')
		{
			$fullstockForceDateChange = ($this->getRequest()->getPost('psop_fullstock_date_force') != $planning->setpsop_fullstock_date_force);
			$planning->setpsop_fullstock_date_force($this->getRequest()->getPost('psop_fullstock_date_force'));
		}
		else 
			$planning->setpsop_fullstock_date_force(null);

		if ($this->getRequest()->getPost('psop_shipping_date_force') != '')
		{
			$shippingForceDateChange = ($this->getRequest()->getPost('psop_shipping_date_force') != $planning->setpsop_shipping_date_force);
			$planning->setpsop_shipping_date_force($this->getRequest()->getPost('psop_shipping_date_force'));
		}
		else 
			$planning->setpsop_shipping_date_force(null);

		//update planning depending of forced dates
		if ($considerationForceDateChange)
		{
			$planning->setConsiderationInformation($order);			
		}
		$planning->setFullStockInformation($order);
		$planning->setShippingInformation($order);
		$planning->setDeliveryInformation($order);
			
		$planning->save();
		
		
		
		//confirm
    	Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Planning saved'));
    	
    	//Redirect
    	$this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));
	}
	
	/**
	 * Reset planning
	 *
	 */
	public function resetAction()
	{
		//retrieves object
		$planningId = $this->getRequest()->getParam('psop_id');
		$planning = mage::getModel('SalesOrderPlanning/Planning')->load($planningId);
		$orderId = $planning->getpsop_order_id();
		$planning->delete();
		
		//confirm
    	Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Planning reseted'));
    	
    	//Redirect
    	$this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));
	}
	
	/**
	 * Create planning
	 *
	 */
	public function CreateAction()
	{
		//create planning
		$orderId = $this->getRequest()->getParam('order_id');
		
		try 
		{
			$order = mage::getModel('sales/order')->load($orderId);
			if ($order->getId())
			{
				mage::helper('SalesOrderPlanning/Planning')->createPlanning($order);
			}
			Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Planning created'));
		}
		catch (Exception $ex)
		{
			Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
		}
		
		//redirect
    	$this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));
    	
	}
	
	public function UpdateAction()
	{
		$planningId = $this->getRequest()->getParam('psop_id');
		$planning = mage::getModel('SalesOrderPlanning/Planning')->load($planningId);
		mage::helper('SalesOrderPlanning/Planning')->updatePlanning($planning->getpsop_order_id());
		Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Planning updated'));
		$this->_redirect('adminhtml/sales_order/view', array('order_id' => $planning->getpsop_order_id()));
	}
}