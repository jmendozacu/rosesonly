<?php

class Egrove_Amex_Model_Amexdata extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('amex/amexdata');
    }
    
}