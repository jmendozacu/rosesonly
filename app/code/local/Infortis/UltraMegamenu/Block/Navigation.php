<?php class Infortis_UltraMegamenu_Block_Navigation extends Mage_Catalog_Block_Navigation
{
    protected $x0b = FALSE;
    protected $x0c = FALSE;
    protected $x0d = FALSE;
    protected $x0e = NULL;

    public function getCacheKeyInfo()
    {
        $x0f = array('CATALOG_NAVIGATION', Mage::app()->getStore()->getId(), Mage::getDesign()->getPackageName(), Mage::getDesign()->getTheme('template'), Mage::getSingleton('customer/session')->getCustomerGroupId(), 'template' => $this->getTemplate(), 'name' => $this->getNameInLayout(), $this->getCurrenCategoryKey(), Mage::helper('ultramegamenu')->getIsOnHome(), (int)Mage::app()->getStore()->isCurrentlySecure(),);
        $x10 = $x0f;
        $x0f = array_values($x0f);
        $x0f = implode('|', $x0f);
        $x0f = md5($x0f);
        $x10['category_path'] = $this->getCurrenCategoryKey();
        $x10['short_cache_id'] = $x0f;
        return $x10;
    }

    protected function _renderCategoryMenuItemHtml($x11, $x12 = 0, $x13 = false, $x14 = false, $x15 = false, $x16 = '', $x17 = '', $x18 = false)
    {
        if (!$x11->getIsActive()) {
            return '';
        }
        $x19 = array();
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $x1a = (array)$x11->getChildrenNodes();
            $x1b = count($x1a);
        } else {
            $x1a = $x11->getChildren();
            $x1b = $x1a->count();
        }
        $x1c = ($x1a && $x1b);
        $x1d = array();
        foreach ($x1a as $x1e) {
            if ($x1e->getIsActive()) {
                $x1d[] = $x1e;
            }
        }
        $x1f = count($x1d);
        $x20 = ($x1f > 0);
        $x21 = Mage::helper('ultramegamenu');
        $x22 = Mage::getModel('catalog/category')->load($x11->getId());
        $x23 = FALSE;
        if ($this->_isWide) {
            $x23 = $x20;
            if ($x21->getCfg('widemenu/show_if_no_children')) {
                $x23 = true;
            }
        }
        $x24 = array();
        $x25 = array();
        $x26 = false;
        $x27 = 0;
        if ($x12 == 0 && $this->_isAccordion == FALSE && $x23) {
            $x28 = $this->_getCatBlock($x22, 'umm_cat_block_right');
            $x29 = 6;
            if ($x2a = $x22->getData('umm_cat_block_proportions')) {
                $x2a = explode("\057", $x2a);
                $x2b = $x2a[0];
                $x2c = $x2a[1];
            } else {
                $x2b = 4;
                $x2c = 2;
            }
            $x27 = $x2b + $x2c;
            if (empty($x28)) {
                $x2b += $x2c;
                $x2c = 0;
                $x2d = 'grid12-12';
            } elseif (!$x20) {
                $x2c += $x2b;
                $x2b = 0;
                $x2e = 'grid12-12';
            } else {
                $x2f = 12 / $x27;
                $x2d = 'grid12-' . ($x2b * $x2f);
                $x2e = 'grid12-' . ($x2c * $x2f);
            }
            $x27 = $x2b + $x2c;
            $x30 = '';
            if ($x30 = $this->_getCatBlock($x22, 'umm_cat_block_top')) {
                $x24[] = '<div class="nav-block nav-block-top grid-full std">';
                $x24[] = $x30;
                $x24[] = '</div>';
            }
            if ($x20) {
                $x31 = 'itemgrid itemgrid-' . $x2b . 'col';
                $x24[] = '<div class="nav-block nav-block-center ' . $x2d . ' ' . $x31 . '">';
                $x25[] = '</div>';
            }
            if ($x28) {
                $x25[] = '<div class="nav-block nav-block-right std ' . $x2e . '">';
                $x25[] = $x28;
                $x25[] = '</div>';
            }
            if ($x30 = $this->_getCatBlock($x22, 'umm_cat_block_bottom')) {
                $x25[] = '<div class="nav-block nav-block-bottom grid-full std">';
                $x25[] = $x30;
                $x25[] = '</div>';
            }
            if (!empty($x24) || !empty($x25)) $x26 = true;
        }
        $x32 = array();
        $x32[] = 'level' . $x12;
        $x32[] = 'nav-' . $this->_getItemPosition($x12);
        if ($this->isCategoryActive($x11)) {
            $x32[] = 'active';
        }
        $x33 = '';
        if ($x15 && $x16) {
            $x32[] = $x16;
            $x33 = ' class="' . $x16 . '"';
        }
        if ($x14) {
            $x32[] = 'first';
        }
        if ($x13) {
            $x32[] = 'last';
        }
        $x34 = ($x20 || $x26) ? true : false;
        if ($x34) {
            $x32[] = 'parent';
        }
        if ($x12 == 1 && $this->_isAccordion == FALSE && $this->_isWide) {
            $x32[] = 'item';
        }
        $x35 = array();
        if (count($x32) > 0) {
            $x35['class'] = implode(' ', $x32);
        }
        if ($x20 && !$x18) {
            $x35['onmouseover'] = 'toggleMenu(this,1)';
            $x35['onmouseout'] = 'toggleMenu(this,0)';
        }
        $x36 = '<li';
        foreach ($x35 as $x37 => $x38) {
            $x36 .= ' ' . $x37 . '="' . str_replace('"', '\"', $x38) . '"';
        }
        $x36 .= '>';
        $x19[] = $x36;
        if ($x12 == 1 && $this->_isAccordion == FALSE && $this->_isWide) {
            if ($x30 = $this->_getCatBlock($x22, 'umm_cat_block_top')) {
                $x19[] = '<div class="nav-block nav-block-level1-top std">';
                $x19[] = $x30;
                $x19[] = '</div>';
            }
        }
        $x39 = $this->_getCategoryLabelHtml($x22, $x12);
        $x3a = '';
        if ($x34 && $x12 == 0 && $this->_isAccordion == FALSE) {
            $x3a = '<span class="caret">&nbsp;</span>';
        }
        $x3b = '';
        if ($this->_showNumProd && $this->_isAccordion) {
            $x3b = '<span class="number"> (' . $this->_getNumberOfProducts($x22) . ')</span>';
        }
        $x19[] = '<a href="' . $this->getCategoryUrl($x11) . '"' . $x33 . '>';
        $x19[] = '<span>' . $this->escapeHtml($x11->getName()) . $x3b . $x39 . '</span>' . $x3a;
        $x19[] = '</a>';
        $x3c = '';
        $x3d = 0;
        foreach ($x1d as $x1e) {
            $x3c .= $this->_renderCategoryMenuItemHtml($x1e, ($x12 + 1), ($x3d == $x1f - 1), ($x3d == 0), false, $x16, $x17, $x18);
            $x3d++;
        }
        if ($x12 == 0 && $this->_isAccordion == FALSE && $this->_isWide) {
            $x17 = 'level0-wrapper dropdown-' . $x27 . 'col';
        }
        if (!empty($x3c) || $x26) {
            if ($this->_isAccordion == TRUE) $x19[] = '<span class="opener">&nbsp;</span>';
            if ($x17) {
                $x19[] = '<div class="' . $x17 . '"><div class="level0-wrapper2">';
            }
            $x19[] = implode("", $x24);
            if (!empty($x3c)) {
                $x19[] = '<ul class="level' . $x12 . '">';
                $x19[] = $x3c;
                $x19[] = '</ul>';
            }
            $x19[] = implode("", $x25);
            if ($x17) {
                $x19[] = '</div></div>';
            }
        }
        if ($x12 == 1 && $this->_isAccordion == FALSE && $this->_isWide) {
            if ($x30 = $this->_getCatBlock($x22, 'umm_cat_block_bottom')) {
                $x19[] = '<div class="nav-block nav-block-level1-bottom std">';
                $x19[] = $x30;
                $x19[] = '</div>';
            }
        }
        $x19[] = '</li>';
        $x19 = implode("\n", $x19);
        return $x19;
    }

    public function renderCategoriesMenuHtml($x3e = FALSE, $x12 = 0, $x16 = '', $x17 = '')
    {
        $this->_isAccordion = $x3e;
        $this->_isWide = Mage::helper('ultramegamenu')->getCfg('mainmenu/wide_menu');
        $x3f = array();
        foreach ($this->getStoreCategories() as $x1e) {
            if ($x1e->getIsActive()) {
                $x3f[] = $x1e;
            }
        }
        $x40 = count($x3f);
        $x41 = ($x40 > 0);
        if (!$x41) {
            return '';
        }
        $x19 = '';
        $x3d = 0;
        foreach ($x3f as $x11) {
            $x19 .= $this->_renderCategoryMenuItemHtml($x11, $x12, ($x3d == $x40 - 1), ($x3d == 0), true, $x16, $x17, true);
            $x3d++;
        }
        return $x19;
    }

    public function renderMe($x3e, $x42 = 0, $x43 = 0)
    {
        $x44 = '';
        $x45 = '';
        if ($x42 === 'parent_no_siblings') {
            if ($x46 = Mage::registry('current_category')) {
                $x44 = $x46->getId();
                $x45 = $x46->getLevel();
            }
        }
        $this->_isAccordion = $x3e;
        $this->_isWide = Mage::helper('ultramegamenu')->getCfg('mainmenu/wide_menu');
        $this->_showNumProd = Mage::helper('ultramegamenu')->getCfg('sidemenu/num_of_products');
        $x12 = 0;
        $x16 = '';
        $x17 = '';
        $x47 = $this->_getParentCategoryId($x42);
        $x48 = $this->_getCategoriesByParent($x47, $x43);
        $x3f = array();
        foreach ($x48 as $x1e) {
            if ($x1e->getIsActive()) {
                if ($x42 === 'parent_no_siblings') {
                    if ($x45 !== '' && $x1e->getLevel() == $x45 && $x1e->getId() != $x44) {
                        continue;
                    }
                }
                $x3f[] = $x1e;
            }
        }
        $x40 = count($x3f);
        $x41 = ($x40 > 0);
        if (!$x41) {
            return '';
        }
        $x19 = '';
        $x3d = 0;
        foreach ($x3f as $x11) {
            $x19 .= $this->_renderCategoryMenuItemHtml($x11, $x12, ($x3d == $x40 - 1), ($x3d == 0), true, $x16, $x17, true);
            $x3d++;
        }
        return $x19;
    }

    protected function _getCategoriesByParent($x47 = 0, $x43 = 0, $x49 = false, $x4a = false, $x4b = true)
    {
        $x11 = Mage::getModel('catalog/category');
        if ($x47 === NULL || !$x11->checkId($x47)) {
            return array();
        }
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $x48 = $this->_getCategoriesByParentFlat($x47, $x43, $x49, $x4a, $x4b);
        } else {
            $x48 = $x11->getCategories($x47, $x43, $x49, $x4a, $x4b);
        }
        return $x48;
    }

    protected function _getCategoriesByParentFlat($x47 = 0, $x43 = 0, $x49 = false, $x4a = false, $x4b = true)
    {
        $x4c = Mage::getResourceModel('catalog/category_flat');
        return $x4c->getCategories($x47, $x43, $x49, $x4a, $x4b);
    }

    protected function _getParentCategoryId($x42)
    {
        $x47 = NULL;
        if ($x42 === 'current') {
            $x46 = Mage::registry('current_category');
            if ($x46) {
                $x47 = $x46->getId();
            }
        } elseif ($x42 === 'parent') {
            $x46 = Mage::registry('current_category');
            if ($x46) {
                $x47 = $x46->getParentId();
            }
        } elseif ($x42 === 'parent_no_siblings') {
            $x46 = Mage::registry('current_category');
            if ($x46) {
                $x47 = $x46->getParentId();
            }
        } elseif ($x42 === 'root' || !$x42) {
            $x47 = Mage::app()->getStore()->getRootCategoryId();
        } elseif (is_numeric($x42)) {
            $x47 = intval($x42);
        }
        $x4d = Mage::helper('ultramegamenu')->getCfg('sidemenu/fallback');
        if ($x47 === NULL && $x4d) {
            $x47 = Mage::app()->getStore()->getRootCategoryId();
        }
        return $x47;
    }

    protected function _getNumberOfProducts($x11)
    {
        return Mage::getModel('catalog/layer')->setCurrentCategory($x11->getID())->getProductCollection()->getSize();
    }

    public function renderBlockTitle()
    {
        $x21 = Mage::helper('ultramegamenu');
        $x46 = Mage::registry('current_category');
        if (!$x46) {
            $x4d = $x21->getCfg('sidemenu/fallback');
            if ($x4d) {
                $x4e = $x21->getCfg('sidemenu/block_name_fallback');
                if ($x4e) {
                    return $x4e;
                }
            }
        }
        $x4f = $this->getBlockName();
        if ($x4f === NULL) {
            $x4f = $x21->getCfg('sidemenu/block_name');
        }
        $x50 = '';
        if ($x46) {
            $x50 = $x46->getName();
        }
        $x4f = str_replace('[current_category]', $x50, $x4f);
        return $x4f;
    }

    protected function _getCatBlock($x22, $x51)
    {
        if (!$this->_tplProcessor) {
            $this->_tplProcessor = Mage::helper('cms')->getBlockTemplateProcessor();
        }
        return $this->_tplProcessor->filter(trim($x22->getData($x51)));
    }

    protected function _getCategoryLabelHtml($x22, $x12)
    {
        $x52 = $x22->getData('umm_cat_label');
        if ($x52) {
            $x53 = trim(Mage::helper('ultramegamenu')->getCfg('category_labels/' . $x52));
            if ($x53) {
                if ($x12 == 0) {
                    return ' <span class="cat-label cat-label-' . $x52 . ' pin-bottom">' . $x53 . '</span>';
                } else {
                    return ' <span class="cat-label cat-label-' . $x52 . '">' . $x53 . '</span>';
                }
            }
        }
        return '';
    }
}