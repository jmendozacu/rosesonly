<?php

class Ant_Citibank_DoController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Credit Card payment"));
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
            "label" => $this->__("Home Page"),
            "title" => $this->__("Home Page"),
            "link" => Mage::getBaseUrl()
        ));

        $breadcrumbs->addCrumb("creditcard", array(
            "label" => $this->__("Credit Card payment"),
            "title" => $this->__("Credit Card payment")
        ));

        $this->renderLayout();
    }
}