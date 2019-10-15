<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE.txt
 *
 * @category   Magpleasure
 * @package    Magpleasure_Adminlogger
 * @copyright  Copyright (c) 2012 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE.txt
 */
class Magpleasure_Adminlogger_Block_Adminhtml_Adminlogger_Renderer_Default extends Mage_Adminhtml_Block_Template
{
    protected $_log;
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("adminlogger/renderer/default.phtml");
    }

    /**
     * @return Magpleasure_Adminlogger_Model_Log
     */
    public function getLog()
    {
        return $this->_log;
    }

    public function setLog($value)
    {
        $this->_log = $value;
        return $this;
    }

    public function getDetails()
    {
        return $this->getLog()->getDetailsCollection();
    }
}