<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config\CustomFields;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * Product Attributes Dropdown Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class ProductAttributes extends Select
{
    /**
     * Product Attribute Collection
     *
     * @var CollectionFactory
     */
    protected $_attributeCollectionFactory;

    /**
     * ProductAttributes constructor
     *
     * @param Context           $context
     * @param CollectionFactory $attributeCollectionFactory
     * @param array             $data
     */
    public function __construct(
        Context           $context,
        CollectionFactory $attributeCollectionFactory,
        array             $data = []
    ) {
        parent::__construct($context, $data);
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
    }

    /**
     * Set Input Name
     *
     * @param string $value
     *
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set dropdown options before render
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->addOption(null, null);
            foreach ($this->_getProductAttributes() as $option) {
                $this->addOption($option->getAttributeCode(), $option->getDefaultFrontendLabel());
            }
        }

        return parent::_toHtml();
    }

    /**
     * Get Data Type Options
     *
     * @return Collection|Attribute[]
     */
    protected function _getProductAttributes()
    {
        $attributes = $this->_attributeCollectionFactory->create();
        $attributes->addVisibleFilter();
        $attributes->addFieldToFilter('backend_type', [
            'neq' => 'static'
        ]);

        return $attributes;
    }
}
