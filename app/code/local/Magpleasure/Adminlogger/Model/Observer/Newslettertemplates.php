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
class Magpleasure_Adminlogger_Model_Observer_Newslettertemplates extends Magpleasure_Adminlogger_Model_Observer
{

    public function NewsletterTemplatesLoad($event)
    {
        $this->createLogRecord(
            $this->getActionGroup('newslettertemplates')->getValue(),
            Magpleasure_Adminlogger_Model_Actiongroup_Newslettertemplates::ACTION_NEWSLETTER_TEMPLATES_LOAD,
            Mage::app()->getRequest()->getParam('id')
        );
    }

    public function NewsletterTemplatesSave($event)
    {
        $template = $event->getObject();
        $log = $this->createLogRecord(
            $this->getActionGroup('newslettertemplates')->getValue(),
            Magpleasure_Adminlogger_Model_Actiongroup_Newslettertemplates::ACTION_NEWSLETTER_TEMPLATES_SAVE,
            $template->getId()
        );
        if ($log){
            $log->addDetails(
                $this->_helper()->getCompare()->diff($template->getData(), $template->getOrigData())
            );
        }
    }

    public function NewsletterTemplatesDelete($event)
    {
        $this->createLogRecord(
            $this->getActionGroup('newslettertemplates')->getValue(),
            Magpleasure_Adminlogger_Model_Actiongroup_Newslettertemplates::ACTION_NEWSLETTER_TEMPLATES_DELETE
        );
    }
}