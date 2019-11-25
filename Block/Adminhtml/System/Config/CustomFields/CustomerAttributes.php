<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config\CustomFields;

use Magento\Customer\Api\Data\AttributeMetadataInterface;
use Magento\Customer\Model\Metadata\AddressMetadata;
use Magento\Customer\Model\Metadata\CustomerMetadata;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * Customer Attributes Dropdown Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class CustomerAttributes extends Select
{
    /**
     * Customer Meta Data
     *
     * @var CustomerMetadata
     */
    protected $_customerMetaData;

    /**
     * Address Meta Data
     *
     * @var AddressMetadata
     */
    protected $_addressMetaData;

    /**
     * CustomerAttributes constructor
     *
     * @param Context          $context
     * @param CustomerMetadata $customerMetadata
     * @param AddressMetadata  $addressMetadata
     * @param array            $data
     */
    public function __construct(
        Context          $context,
        CustomerMetadata $customerMetadata,
        AddressMetadata  $addressMetadata,
        array            $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerMetaData = $customerMetadata;
        $this->_addressMetaData = $addressMetadata;
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
        $options = [];
        $options[] = [
            'value' => null,
            'label' => null,
        ];

        $customerAttributes = $this->_getCustomerAttributes();
        if (!empty($customerAttributes)) {
            $options[] = [
                'value' => $customerAttributes,
                'label' => __('Customer Attributes'),
            ];
        }

        $customerAddressAttributes = $this->_getCustomerAddressAttributes();
        if (!empty($customerAddressAttributes)) {
            $options[] = [
                'value' => $customerAddressAttributes,
                'label' => __('Address Attributes'),
            ];
        }

        $this->setOptions($options);

        return parent::_toHtml();
    }

    /**
     * Get Customer Attributes
     *
     * @return array|AttributeMetadataInterface[]
     */
    protected function _getCustomerAttributes()
    {
        $invalid = [
            'password_hash',
            'default_billing',
            'default_shipping',
            'failures_num',
            'first_failure',
            'rp_token',
            'rp_token_created_at',
            'lock_expires',
        ];

        $options = [];
        /** @var AttributeMetadataInterface[] $attributes */
        try {
            $attributes = $this->_customerMetaData->getAllAttributesMetadata();
        } catch (LocalizedException $e) {
            return $options;
        }

        foreach ($attributes as $key => $attribute) {
            if (in_array($attribute->getAttributeCode(), $invalid)) {
                continue;
            }

            $options[] = [
                'value' => 'customer:' . $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel()
            ];
        }

        return $options;
    }

    /**
     * Get Customer Address Attributes
     *
     * @return array|AttributeMetadataInterface[]
     */
    protected function _getCustomerAddressAttributes()
    {
        $options = [];
        try {
            /** @var AttributeMetadataInterface[] $attributes */
            $attributes = $this->_addressMetaData->getAllAttributesMetadata();
        } catch (LocalizedException $e) {
            return $options;
        }

        foreach ($attributes as $key => $attribute) {
            $options[] = [
                'value' => 'address:' . $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel()
            ];
        }

        return $options;
    }
}
