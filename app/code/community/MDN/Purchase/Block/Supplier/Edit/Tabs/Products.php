<?php

class MDN_Purchase_Block_Supplier_Edit_Tabs_Products extends Mage_Adminhtml_Block_Widget_Grid {

    private $_supplier_id;

    /**
     * Set supplier
     *
     * @param unknown_type $value
     */
    public function setSupplierId($value) {
        $this->_supplier_id = $value;
        return $this;
    }

    /**
     * Get supplier
     *
     * @param unknown_type $value
     */
    public function getSupplierId() {
        return $this->_supplier_id;
    }

    public function __construct() {
        parent::__construct();
        $this->setId('SupplierProductsGrid');
        $this->_parentTemplate = $this->getTemplate();
        $this->setEmptyText($this->__('No items'));
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('supplier_products');
        $this->setDefaultSort('sku', 'asc');
    }

    /**
     * Charge la collection
     *
     * @return unknown
     */
    protected function _prepareCollection() {
        //charge les mouvements de stock
        // Phuoc's code: Code nhu the nay co the bi sot san pham o warehouse,
        // nhung chua nghi ra cach toi uu hon
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $query = "SELECT a.pps_wh_num
                    FROM (SELECT pps_supplier_num, pps_wh_num, count(*) AS count_col
                        FROM purchase_product_supplier
                        WHERE pps_supplier_num = ".$this->_supplier_id."
                        GROUP BY pps_supplier_num, pps_wh_num
                        ORDER BY count_col DESC
                        limit 0,1) a";
        $result = $readConnection->query($query);
        $row = $result->fetch();
        ////
        $collection = mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('manufacturer')
                ->addAttributeToSelect('entity_id')
                ->joinTable(mage::getModel('Purchase/Constant')->getTablePrefix() . 'purchase_product_supplier', 'pps_product_id=entity_id', array(
                    'pps_reference' => 'pps_reference',
                    'pps_product_id' => 'pps_product_id',
                    'pps_supplier_num' => 'pps_supplier_num',
                    'pps_is_default_supplier' => 'pps_is_default_supplier',
                    'pps_quantity_product' => 'pps_quantity_product',
                    'pps_last_price' => 'pps_last_price',
                    'pps_can_dropship' => 'pps_can_dropship',
                    'pps_wh_num' => 'pps_wh_num'
                        )
                )
//                ->groupByAttribute(array(
//                    'id',
//                    'entity_id',
//                    'name',
//                    'manufacturer',
//                    'pps_reference',
//                    'pps_product_id',
//                    'pps_supplier_num',
//                    'pps_is_default_supplier',
//                    'pps_quantity_product',
//                    'pps_last_price',
//                    'pps_can_dropship'))
                ->addFieldToFilter('pps_supplier_num', $this->_supplier_id)
                ->addFieldToFilter('pps_wh_num', $row['pps_wh_num']);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * D�fini les colonnes du grid
     *
     * @return unknown
     */
    protected function _prepareColumns() {
        $this->addColumn('details', array(
            'header' => Mage::helper('purchase')->__('Details'),
            'renderer' => 'MDN_Purchase_Block_Widget_Column_Renderer_SupplyNeedsDetails',
            'align' => 'center',
            'filter' => false,
            'sortable' => false,
            'product_id_field_name' => 'entity_id',
            'product_name_field_name' => 'name'
        ));

        $this->addColumn('manufacturer', array(
            'header' => Mage::helper('purchase')->__('Manufacturer'),
            'index' => 'manufacturer',
            'type' => 'options',
            'options' => $this->getManufacturersAsArray(),
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('Sku'),
            'index' => 'sku',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name',
        ));

        $this->addColumn('pps_reference', array(
            'header' => Mage::helper('catalog')->__('Supplier sku'),
            'index' => 'pps_reference',
        ));

        $this->addColumn('pps_quantity_product', array(
            'header' => Mage::helper('catalog')->__('Supplier stock'),
            'index' => 'pps_quantity_product',
            'align' => 'center'
        ));

        $this->addColumn('pps_is_default_supplier', array(
            'header' => Mage::helper('purchase')->__('Is default'),
            'index' => 'pps_is_default_supplier',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('catalog')->__('Yes'),
                '0' => Mage::helper('catalog')->__('No'),
            ),
            'align' => 'center'
        ));

        $this->addColumn('pps_can_dropship', array(
            'header' => Mage::helper('purchase')->__('Can dropship'),
            'index' => 'pps_can_dropship',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('catalog')->__('Yes'),
                '0' => Mage::helper('catalog')->__('No'),
            ),
            'align' => 'center'
        ));

        $this->addColumn('pps_last_price', array(
            'header' => Mage::helper('purchase')->__('Price'),
            'index' => 'pps_last_price',
            'type' => 'number'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/ProductsGrid', array('_current' => true, 'sup_id' => $this->_supplier_id));
    }

    public function getGridParentHtml() {
        $templateName = Mage::getDesign()->getTemplateFilename($this->_parentTemplate, array('_relative' => true));
        return $this->fetchView($templateName);
    }

    public function getRowUrl($row) {
        return $this->getUrl('AdvancedStock/Products/Edit', array('product_id' => $row->getId()));
    }

    /**
     * Return manufacturers list as array
     *
     */
    public function getManufacturersAsArray() {
        $retour = array();

        //recupere la liste des manufacturers
        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', 'manufacturer') // This can be changed to any attribute code
                ->load(false);
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $manufacturers = $attribute->getSource()->getAllOptions(false);

        //ajoute au menu
        foreach ($manufacturers as $manufacturer) {
            $retour[$manufacturer['value']] = $manufacturer['label'];
        }

        return $retour;
    }

}
