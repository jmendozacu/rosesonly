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
class Magpleasure_Adminlogger_Model_Actiongroup_Cmspages extends Magpleasure_Adminlogger_Model_Actiongroup_Abstract
{
    protected $_typeValue = 5;
    const ACTION_PAGES_LOAD = 1;
    const ACTION_PAGES_SAVE = 2;
    const ACTION_PAGES_DELETE = 3;

    public function getLabel()
    {
        return $this->_helper()->__("Cms Pages");
    }

    protected function _getActions()
    {
        return array(
            array('value' => self::ACTION_PAGES_LOAD, 'label' => $this->_helper()->__("Load")),
            array('value' => self::ACTION_PAGES_SAVE, 'label' => $this->_helper()->__("Save")),
            array('value' => self::ACTION_PAGES_DELETE, 'label' => $this->_helper()->__("Delete")),
        );
    }

    public function canDisplayEntity()
    {
        return true;
    }

    public function getModelType()
    {
        return 'cms/page';
    }

    public function getFieldKey()
    {
        return 'title';
    }

    public function getUrlPath()
    {
        return 'adminhtml/cms_page/edit';
    }

    public function getUrlIdKey()
    {
        return 'page_id';
    }
}