<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @Author     Ocean <ocean@gentotech.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class Ocean_CustomerAttributes_Model_Resource_Sales_Abstract extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Used us prefix to name of column table
     *
     * @var null | string
     */
    protected $_columnPrefix       = 'customer';

    /**
     * Primery key auto increment flag
     *
     * @var bool
     */
    protected $_isPkAutoIncrement  = false;

    /**
     * Main entity resource model name
     * Should be overwritten in subclasses.
     *
     * @var string
     */
    protected $_parentResourceModelName = '';

    /**
     * Return column name for attribute
     *
     * @param Mage_Customer_Model_Attribute $attribute
     * @return string
     */
    protected function _getColumnName(Mage_Customer_Model_Attribute $attribute)
    {
        $columnName = $attribute->getAttributeCode();
        if ($this->_columnPrefix) {
            $columnName = sprintf('%s_%s', $this->_columnPrefix, $columnName);
        }
        return $columnName;
    }

    /**
     * Saves a new attribute
     *
     * @param Mage_Customer_Model_Attribute $attribute
     * @return Ocean_CustomerAttributes_Model_Resource_Sales_Abstract
     */
    public function saveNewAttribute(Mage_Customer_Model_Attribute $attribute)
    {
        $backendType = $attribute->getBackendType();
        if ($backendType == Mage_Customer_Model_Attribute::TYPE_STATIC) {
            return $this;
        }

        switch ($backendType) {
            case 'datetime':
                $definition = array(
                    'type'      => Varien_Db_Ddl_Table::TYPE_DATE,
                );
                break;
            case 'decimal':
                $definition = array(
                    'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                    'length'    => 12,4,
                );
                break;
            case 'int':
                $definition = array(
                    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                );
                break;
            case 'text':
                $definition = array(
                    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                );
                break;
            case 'varchar':
                $definition = array(
                    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                    'length'    => 255,
                );
                break;
            default:
                return $this;
        }

        $columnName = $this->_getColumnName($attribute);
        $definition['comment'] = ucwords(str_replace('_', ' ', $columnName));
        $this->_getWriteAdapter()->addColumn($this->getMainTable(), $columnName, $definition);

        return $this;
    }

    /**
     * Deletes an attribute
     *
     * @param Mage_Customer_Model_Attribute $attribute
     * @return Ocean_CustomerAttributes_Model_Resource_Sales_Abstract
     */
    public function deleteAttribute(Mage_Customer_Model_Attribute $attribute)
    {
        $this->_getWriteAdapter()->dropColumn($this->getMainTable(), $this->_getColumnName($attribute));
        return $this;
    }

    /**
     * Return resource model of the main entity
     *
     * @return Mage_Core_Model_Resource_Abstract | null
     */
    protected function _getParentResourceModel()
    {
        if (!$this->_parentResourceModelName) {
            return null;
        }
        return Mage::getResourceSingleton($this->_parentResourceModelName);
    }

    /**
     * Check if main entity exists in main table.
     * Need to prevent errors in case of multiple customer log in into one account.
     *
     * @param Ocean_CustomerAttributes_Model_Sales_Abstract $sales
     * @return bool
     */
    public function isEntityExists(Ocean_CustomerAttributes_Model_Sales_Abstract $sales)
    {
        if (!$sales->getId()) {
            return false;
        }

        $resource = $this->_getParentResourceModel();
        if (!$resource) {
            /**
             * If resource model is absent, we shouldn't check the database for if main entity exists.
             */
            return true;
        }

        $parentTable = $resource->getMainTable();
        $parentIdField = $resource->getIdFieldName();
        $select = $this->_getWriteAdapter()->select()
            ->from($parentTable, $parentIdField)
            ->forUpdate(true)
            ->where("{$parentIdField} = ?", $sales->getId());
        if ($this->_getWriteAdapter()->fetchOne($select)) {
            return true;
        }
        return false;
    }
}