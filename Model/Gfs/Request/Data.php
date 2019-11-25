<?php

namespace JustShout\Gfs\Model\Gfs\Request;

use JustShout\Gfs\Helper\Config;
use JustShout\Gfs\Model\Gfs\Cookie;
use Magento\Framework\DataObject;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel;
use Magento\Checkout\Model\Session;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Directory\Model\Country;
use Magento\Directory\Model\CountryFactory;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Gfs Request Data Model
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Data
{
    /**
     * Gfs Config Helper
     *
     * @var Config
     */
    protected $_config;

    /**
     * Checkout Session
     *
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * Product Factory
     *
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * Customer Factory
     *
     * @var CustomerFactory
     */
    protected $_customerFactory;

    /**
     * Store Manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Country Factory
     *
     * @var CountryFactory
     */
    protected $_countryFactory;

    /**
     * Json
     *
     * @var Json
     */
    protected $_json;

    /**
     * Gfs Address Cookie
     *
     * @var Cookie\Address
     */
    protected $_addressCookie;

    /**
     * Data constructor
     *
     * @param Config $config
     * @param Session $checkoutSession
     * @param ProductFactory $productFactory
     * @param CustomerFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param CountryFactory $countryFactory
     * @param Json $json
     * @param Cookie\Address $addressCookie
     */
    public function __construct(
        Config $config,
        Session $checkoutSession,
        ProductFactory $productFactory,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        CountryFactory $countryFactory,
        Json $json,
        Cookie\Address $addressCookie
    )
    {
        $this->_config = $config;
        $this->_checkoutSession = $checkoutSession;
        $this->_productFactory = $productFactory;
        $this->_customerFactory = $customerFactory;
        $this->_storeManager = $storeManager;
        $this->_countryFactory = $countryFactory;
        $this->_json = $json;
        $this->_addressCookie = $addressCookie;
    }

    /**
     * This method will generate the request data object that is used in the checkout widget
     *
     * @return array
     */
    public function getGfsData()
    {
        $data = [];
        $request = [];
        $quote = $this->getQuote();
        if (!$quote->getId() || !$this->_getQuoteAddress()->hasData()) {
            return $data;
        }

        $request['options'] = $this->_getOptions();
        $request['order'] = $this->_getOrder();

        return $request;
    }

    /**
     * This will get the customers full address as a string
     *
     * @return string
     */
    public function getInitialAddress()
    {
        $address = $this->_getQuoteAddress();
        if (!$this->_getQuoteAddress()->hasData()) {
            return '';
        }

        $initialAddress = [];
        foreach (explode("\n", $address->getStreet()) as $street) {
            $initialAddress[] = $street;
        }
        $initialAddress[] = $address->getCity();
        $initialAddress[] = $address->getPostcode();
        /** @var Country $countryModel */
        $countryModel = $this->_countryFactory->create();
        /** @var Country $country */
        $country = $countryModel->loadByCode($address->getCountryId());
        $initialAddress[] = $country->getName();

        return implode(', ', $initialAddress);
    }

    /**
     * Get Request Options
     *
     * @return array
     */
    protected function _getOptions()
    {
        $date = new \DateTime();
        $quote = $this->getQuote();
        $range = $this->_getDateRange();

        return [
            'startDate' => $date->format('Y-m-d'),
            'endDate'   => $date->modify('+' . $range . ' day')->format('Y-m-d'),
            'currency'  => $quote->getQuoteCurrencyCode(),
            'droppoints' => [
                'max'    => 50,
                'radius' => 50000,
            ],
            'stores' => [
                'max'    => 50,
                'radius' => 5000,
            ],
        ];
    }

    /**
     * Get Request Order
     *
     * @return array
     */
    protected function _getOrder()
    {
        $order = [];
        $quote = $this->getQuote();

        $order['delivery'] = $this->_getOrderDelivery();
        $order['packs'] = $this->_getOrderPacks();
        $order['price'] = $quote->getSubtotal();
        $order['items'] = $this->_getOrderItems();

        $customFields = $this->_getOrderCustomFields();
        if ($customFields) {
            $order['additional'] = $customFields;
        }

        return $order;
    }

    /**
     * Get Delivery Details
     *
     * @return array
     */
    protected function _getOrderDelivery()
    {
        $data = [];
        $address = $this->_getQuoteAddress();

        $data['street'] =  str_replace("\n", ',', $address->getStreet());
        $data['city'] = $address->getCity();
        if ($address->getRegion()) {
            $data['state'] = $address->getRegion();
        } else if ($address->getRegionCode()) {
            $data['state'] = $address->getRegionCode();
        }

        $data['zip'] = $address->getPostcode();
        $data['country'] = $address->getCountryId();

        return [
            'origin' => $data,
            'destination' => $data,
        ];
    }

    /**
     * Get Order Packs
     *
     * @return int
     */
    protected function _getOrderPacks()
    {
        $packs = 0;
        $quote = $this->getQuote();
        foreach ($quote->getAllVisibleItems() as $item) {
            $packs += (int) $item->getQty();
        }

        return $packs;
    }

    /**
     * Get Order Items
     *
     * @return array
     */
    protected function _getOrderItems()
    {
        $items = [];
        $quote = $this->getQuote();
        foreach ($quote->getAllVisibleItems() as $item) {
            $itemData = [
                'Description' => $item->getName(),
                'price'       => (float) $item->getPriceInclTax(),
                'ProductCode' => $item->getSku(),
                'sku'         => $item->getSku(),
                'quantity'    => $item->getQty(),
            ];
            $dimensions = $this->_getItemDimensions($item);
            if (!empty($dimensions)) {
                $itemData = array_merge($itemData, $dimensions);
            }
            $customFields = $this->_getItemCustomFields($item);
            if (!empty($customFields)) {
                $itemData['additionalData'] = $customFields;
            }
            $items[] = $itemData;
        }

        return $items;
    }

    /**
     * Get Current Quote
     *
     * @return Quote
     */
    public function getQuote()
    {
        return $this->_checkoutSession->getQuote();
    }

    /**
     * When using the checkout, the shipping address is not saved to the quote when initially going through
     * so the address is stored in the checkout session.
     *
     * @return DataObject|Quote\Address
     */
    protected function _getQuoteAddress()
    {
        $address = new DataObject();
        $gfsAddress = $this->_addressCookie->get();
        if (!$gfsAddress) {
            return $address;
        }
        $data = $this->_json->unserialize($gfsAddress);
        if (is_array($data)) {
            $address->addData($data);
        }

        return $address;
    }

    /**
     * @param Quote\Item $item
     *
     * @return array
     */
    protected function _getItemDimensions($item)
    {
        $weight = (float) $item->getWeight();
        if (!$weight) {
            return [];
        }

        $dimensions = [
            'weight' => [
                'unit'  => 'kg',
                'value' => $weight,
            ],
        ];

        $height = $this->_getItemHeight($item);
        if ($height) {
            $dimensions['dimensions']['height'] = $height;
        }

        $width = $this->_getItemWidth($item);
        if ($width) {
            $dimensions['dimensions']['width'] = $width;
        }

        $length = $this->_getItemLength($item);
        if ($length) {
            $dimensions['dimensions']['length'] = $length;
        }

        if ($height || $width || $length) {
            $dimensions['dimensions']['unit'] = $this->_config->getMetricDimensionUnit();
        }

        return $dimensions;
    }

    /**
     * Get Item Height
     *
     * @param Quote\Item $item
     *
     * @return float
     */
    protected function _getItemHeight($item)
    {
        $attr = $this->_config->getHeightAttribute();
        if (!$attr) {
            return 0.00;
        }

        return (float) $this->_getProductAttributeValue($item->getProduct()->getId(), $attr, 'double');
    }

    /**
     * Get Item Width
     *
     * @param Quote\Item $item
     *
     * @return float
     */
    protected function _getItemWidth($item)
    {
        $attr = $this->_config->getWidthAttribute();
        if (!$attr) {
            return 0.00;
        }

        return (float) $this->_getProductAttributeValue($item->getProduct()->getId(), $attr, 'double');
    }

    /**
     * Get Item Length
     *
     * @param Quote\Item $item
     *
     * @return float
     */
    protected function _getItemLength($item)
    {
        $attr = $this->_config->getLengthAttribute();
        if (!$attr) {
            return 0.00;
        }

        return (float) $this->_getProductAttributeValue($item->getProduct()->getId(), $attr, 'double');
    }

    /**
     * This method will get the custom fields object per item
     *
     * @param Quote\Item $item
     *
     * @return array
     */
    protected function _getItemCustomFields($item)
    {
        $fields = [];
        $fieldNumber = 1;
        foreach ($this->_config->getItemCustomFields() as $field) {
            $attributeCode = isset($field['value']) ? $field['value'] : null;
            if (!$attributeCode) {
                continue;
            }
            $type = isset($field['type']) ? $field['type'] : 'String';
            $value = $this->_getProductAttributeValue(
                (int) $item->getProduct()->getId(),
                $attributeCode,
                $type
            );

            if (!$value && $value !== 0) {
                continue;
            }

            $fields[] = [
                'name'  => $fieldNumber,
                'value' => $value
            ];

            $fieldNumber++;
        }

        return $fields;
    }

    /**
     * This method will get the attribute values by product id
     *
     * @param int    $productId
     * @param string $attributeCode
     * @param string $type
     *
     * @return float|int|null|string
     */
    protected function _getProductAttributeValue($productId, $attributeCode, $type)
    {
        try {
            /** @var Product $productModel */
            $productModel = $this->_productFactory->create();
            /** @var ResourceModel\Product $resourceModel */
            $resourceModel = $productModel->getResource();
            $attribute = $resourceModel->getAttribute($attributeCode);
            if (!$attribute) {
                throw new \Exception();
            }

            $attributeValue = $resourceModel->getAttributeRawValue(
                $productId,
                $attributeCode,
                $this->_getStoreId()
            );

            if (!$attributeValue || is_array($attributeValue)) {
                throw new \Exception();
            }

            if ($attribute->usesSource()) {
                // Handle multi-select attributes
                $labels = [];
                foreach (explode(',', $attributeValue) as $optionId) {
                    $labels[] = $attribute->getSource()->getOptionText($optionId);
                }
                $value = implode(',', $labels);
            } else {
                $value = $attributeValue;
            }

            if ($value !== null) {
                $value = $this->_formatCustomField($value, $type);
            }

        } catch (\Exception $e) {
            $value = null;
        }

        return $value;
    }

    /**
     * This method will get the custom fields for the order object
     *
     * @return array
     */
    protected function _getOrderCustomFields()
    {
        $fields = [];
        $customer = $this->_getQuoteCustomer();
        if (!$customer || !$customer->getId()) {
            return $fields;
        }
        $fieldNumber = 1;
        foreach ($this->_config->getCustomerCustomFields() as $field) {
            $attributeCode = isset($field['value']) ? $field['value'] : null;
            if (!$attributeCode) {
                continue;
            }
            $type = isset($field['type']) ? $field['type'] : 'String';
            $value = $this->_getCustomerAttributeValue($customer, $attributeCode, $type);

            if (!$value && $value !== 0) {
                continue;
            }

            $fields[] = [
                'name'  => $fieldNumber,
                'value' => $value
            ];

            $fieldNumber++;
        }

        return $fields;
    }

    /**
     * This method will get the attribute value for either a customer or a customer address
     *
     * @param Customer $customer
     * @param string   $attributeCode
     * @param string   $type
     *
     * @return float|int|null|string
     */
    protected function _getCustomerAttributeValue($customer, $attributeCode, $type)
    {
        try {
            $attributeCodeSegments = explode(':', $attributeCode);
            $attributeCodeType = isset($attributeCodeSegments[0]) ? $attributeCodeSegments[0] : null;
            $attributeCode = isset($attributeCodeSegments[1]) ? $attributeCodeSegments[1] : null;
            if (!$attributeCodeType || !$attributeCode) {
                throw new \Exception();
            }

            switch ($attributeCodeType) {
                case 'address':
                    $address = $customer->getDefaultShippingAddress();
                    $value = $address->getData($attributeCode);
                    break;
                case 'customer':
                    $value = $customer->getData($attributeCode);
                    break;
                default:
                    $value = null;
            }

            if ($value !== null) {
                $value = $this->_formatCustomField($value, $type);
            }
        } catch (\Exception $e) {
            $value = null;
        }

        return $value;
    }

    /**
     * This method will load the customer entity for the quote
     *
     * @return Customer|bool
     */
    protected function _getQuoteCustomer()
    {
        $customerId = (int) $this->getQuote()->getCustomerId();
        if (!$customerId) {
            return false;
        }

        /** @var Customer $customerModel */
        $customerModel = $this->_customerFactory->create();
        $customer = $customerModel->load($customerId);
        if (!$customer->getId()) {
              return false;
        }

        return $customer;
    }

    /**
     * This method will format the custom field value based on its data type
     *
     * @param string $value
     * @param string $type
     *
     * @return float|int|string
     */
    protected function _formatCustomField($value, $type)
    {
        switch ($type) {
            case 'Integer':
                $value = (int) $value;
                break;
            case 'double':
                $value = (float) $value;
                break;
            default:
                $value = (string) $value;
        }

        return $value;
    }

    /**
     * Get Date Range
     *
     * @return int
     */
    protected function _getDateRange()
    {
        $range = $this->_config->getDateRange();
        if (!$range || $range < 14) {
            $range = 14;
        }

        if ($range > 179) {
            $range = 179;
        }

        return $range;
    }

    /**
     * Get Store Id
     *
     * @return int
     */
    protected function _getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
}
