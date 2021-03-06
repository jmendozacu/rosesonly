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
class Magpleasure_Adminlogger_Model_Mysql4_Log extends Mage_Core_Model_Mysql4_Abstract
{
    const MYSQL_ZEND_DATE_FORMAT = 'yyyy-MM-dd HH:mm:ss';

    public function _construct()
    {
        $this->_init('adminlogger/log', 'log_id');
    }

    public function clearLog($keepDays)
    {
        $writeAdapter = $this->_getWriteAdapter();
        $select = new Zend_Db_Select($writeAdapter);

        $dateTime = new Zend_Date();
        $dateTime->subDay((int)$keepDays);

        $select
            ->from($this->getMainTable())
            ->where('action_time < ?', $dateTime->toString(self::MYSQL_ZEND_DATE_FORMAT));

        $writeAdapter->beginTransaction();
        $deleteSql = $writeAdapter->deleteFromSelect($select, $this->getMainTable());
        $writeAdapter->query($deleteSql);
        $writeAdapter->commit();
        return $this;
    }

}