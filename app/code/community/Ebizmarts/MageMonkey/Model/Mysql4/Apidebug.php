<?php

/**
 * Apidebug Mysql4 model
 *
 * @category   Ebizmarts
 * @package    Ebizmarts_MageMonkey
 * @author     Ebizmarts Team <info@ebizmarts.com>
 */
class Ebizmarts_MageMonkey_Model_Mysql4_Apidebug extends Mage_Core_Model_Mysql4_Abstract
{

	/**
	 * Initialize
	 *
	 * @return void
	 */
    public function _construct()
    {
        $this->_init('monkey/apidebug', 'debug_id');
    }
}