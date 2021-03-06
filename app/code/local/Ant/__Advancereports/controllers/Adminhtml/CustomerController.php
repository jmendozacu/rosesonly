<?php

class Ant_Advancereports_Adminhtml_CustomerController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {       
        $this->loadLayout();       
        return $this;
    }

    public function indexAction() {       
        $this->_initAction();       
        $this->renderLayout();  
    }

    public function exportCsvAction() {        
        $fields = explode(",",$_GET["fields"]);
        $fields_index =array();
        foreach($fields as $field){
            if(strlen(trim($field))>0)
               $fields_index[]=$field; 
        }
        $fileName = 'advancereports.csv';
        $content = $this->getLayout()->createBlock('advancereports/adminhtml_customer_grid')
                ->getCsv($fields_index);
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fields = explode(",",$_GET["fields"]);
        $fields_index =array();
        foreach($fields as $field){
            if(strlen(trim($field))>0)
               $fields_index[]=$field; 
        }
        $fileName = 'advancereports.xml';
        $content = $this->getLayout()->createBlock('advancereports/adminhtml_customer_grid')
                ->getXml($fields_index);
        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}