<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml shipment create
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Sales_Order_Shipment_View extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        $this->_objectId = 'shipment_id';
        $this->_controller = 'sales_order_shipment';
        $this->_mode = 'view';

        parent::__construct();

        $this->_removeButton('reset');
        $this->_removeButton('delete');
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/emails')) {
            $this->_updateButton('save', 'label', Mage::helper('sales')->__('Send Tracking Information'));
            $this->_updateButton('save', 'onclick', "deleteConfirm('"
                    . Mage::helper('sales')->__('Are you sure you want to send Shipment email to customer?')
                    . "', '" . $this->getEmailUrl() . "')"
            );
        }
        $this->_removeButton('save');
        /*
          if ($this->getShipment()->getId()) {
          $this->_addButton('print', array(
          'label'     => Mage::helper('sales')->__('Print'),
          'class'     => 'save',
          'onclick'   => 'setLocation(\''.$this->getPrintUrl().'\')'
          )
          );
          $shipmentIds = $this->getRequest()->getPost('shipment_ids');
          }
         */

        $this->_addButton('print_gift_message', array(
            'label' => Mage::helper('sales')->__('Print Gift Message'),
            'onclick' => 'window.open(\'' . $this->getPrintMsgUrl() . '\')',
            'class' => 'save'
        ));

        $this->_addButton('print_gift_message_2', array(
            'label' => Mage::helper('sales')->__('Print Gift Message 2'),
            'onclick' => 'window.open(\'' . $this->getPrintMsg2Url() . '\')',
            'class' => 'save'
        ));

        $this->_addButton('print_gift_message_3', array(
            'label' => Mage::helper('sales')->__('Print Gift Envelope'),
            'onclick' => 'window.open(\'' . $this->getPrintMsg3Url() . '\')',
            'class' => 'save'
        ));

        $this->_addButton('do_print', array(
            'label' => Mage::helper('sales')->__('DO'),
            'onclick' => 'window.open(\'' . $this->getDOPrintUrl() . '\')',
            'target' => '_blank',
            'class' => 'save'
        ));
    }

    /**
     * Retrieve shipment model instance
     *
     * @return Mage_Sales_Model_Order_Shipment
     */
    public function getShipment() {
        return Mage::registry('current_shipment');
    }

    public function getHeaderText() {
        if ($this->getShipment()->getEmailSent()) {
            $emailSent = Mage::helper('sales')->__('the shipment email was sent');
        } else {
            $emailSent = Mage::helper('sales')->__('the shipment email is not sent');
        }
        return Mage::helper('sales')->__('Shipment #%1$s | %3$s (%2$s)', $this->getShipment()->getIncrementId(), $emailSent, $this->formatDate($this->getShipment()->getCreatedAtDate(), 'medium', true));
    }

    public function getBackUrl() {
        return $this->getUrl(
                        '*/sales_order/view', array(
                    'order_id' => $this->getShipment()->getOrderId(),
                    'active_tab' => 'order_shipments'
                ));
    }

    public function getEmailUrl() {
        return $this->getUrl('*/sales_order_shipment/email', array('shipment_id' => $this->getShipment()->getId()));
    }

    public function getPrintUrl() {
        return $this->getUrl('*/*/print', array(
                    'invoice_id' => $this->getShipment()->getId()
                ));
    }

    public function updateBackButtonUrl($flag) {
        if ($flag) {
            if ($this->getShipment()->getBackUrl()) {
                return $this->_updateButton('back', 'onclick', 'setLocation(\'' . $this->getShipment()->getBackUrl() . '\')');
            }
            return $this->_updateButton('back', 'onclick', 'setLocation(\'' . $this->getUrl('*/sales_shipment/') . '\')');
        }
        return $this;
    }

    public function getPrintMsgUrl() {
        return $this->getUrl('*/*/printm', array(
                    'order_id' => $this->getShipment()->getOrderId(),
                    'msg' => true
                ));
    }

    public function getPrintMsg2Url() {
        return $this->getUrl('*/*/printm2', array(
                    'order_id' => $this->getShipment()->getOrderId(),
                    'msg' => true
                ));
    }

    public function getPrintMsg3Url() {
        return $this->getUrl('*/*/printm3', array(
            'order_id' => $this->getShipment()->getOrderId(),
            'msg' => true
        ));
    }

    public function getDOPrintUrl() {
        return $this->getUrl('*/*/doprint', array(
                    'order_id' => $this->getShipment()->getOrderId(),
                ));
    }

}