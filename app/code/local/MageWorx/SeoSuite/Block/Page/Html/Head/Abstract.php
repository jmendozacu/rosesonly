<?php
/**
 * MageWorx
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mageworx.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 * or send an email to sales@mageworx.com
 *
 * @category   MageWorx
 * @package    MageWorx_SeoSuite
 * @copyright  Copyright (c) 2010 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * SEO Suite extension
 *
 * @category   MageWorx
 * @package    MageWorx_SeoSuite
 * @author     MageWorx Dev Team <dev@mageworx.com>
 */
if ((string)Mage::getConfig()->getModuleConfig('MageWorx_Accelerator')->active == 'true'){
    class MageWorx_SeoSuite_Block_Page_Html_Head_Abstract extends MageWorx_Accelerator_Block_Page_Html_Head {}
} elseif ((string)Mage::getConfig()->getModuleConfig('Fooman_Speedster')->active == 'true'){
    class MageWorx_SeoSuite_Block_Page_Html_Head_Abstract extends Fooman_Speedster_Block_Page_Html_Head {}
} elseif ((string)Mage::getConfig()->getModuleConfig('Mage_External')->active == 'true'){
    class MageWorx_SeoSuite_Block_Page_Html_Head_Abstract extends Mage_External_Block_Html_Head {}
} elseif ((string)Mage::getConfig()->getModuleConfig('Inchoo_Xternal')->active == 'true'){
    class MageWorx_SeoSuite_Block_Page_Html_Head_Abstract extends Inchoo_Xternal_Block_Html_Head {}    
} else {
    class MageWorx_SeoSuite_Block_Page_Html_Head_Abstract extends Mage_Page_Block_Html_Head {}
}