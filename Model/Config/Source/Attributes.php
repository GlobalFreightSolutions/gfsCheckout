<?php

namespace JustShout\Gfs\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Attributes Source
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Attributes implements OptionSourceInterface
{
    /**
     * Product Attribute Collection
     *
     * @var CollectionFactory
     */
    protected $_attributeCollectionFactory;

    /**
     * Attributes
     *
     * @param CollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        CollectionFactory $attributeCollectionFactory
    ) {
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = [
            'value' => '',
            'label' => __('Please select an attribute'),
        ];

        foreach ($this->_getProductAttributes() as $option) {
            $options[] = [
                'value' => $option->getAttributeCode(),
                'label' => $option->getDefaultFrontendLabel(),
            ];
        }

        return $options;
    }

    /**
     * Get Data Type Options
     *
     * @return Collection|Attribute[]
     */
    protected function _getProductAttributes()
    {
        /** @var Collection $attributes */
        $attributes = $this->_attributeCollectionFactory->create();
        $attributes->addVisibleFilter();
        $attributes->addFieldToFilter('backend_type', [
            'neq' => 'static'
        ]);
        $attributes->setOrder(Attribute::FRONTEND_LABEL, Collection::SORT_ORDER_ASC);

        return $attributes;
    }
}
