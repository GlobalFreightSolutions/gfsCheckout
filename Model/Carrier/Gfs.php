<?php

namespace JustShout\Gfs\Model\Carrier;

use JustShout\Gfs\Helper\Config;
use JustShout\Gfs\Model\Gfs\Client;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

/**
 * Carrier Model
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Gfs extends AbstractCarrier implements CarrierInterface
{
    /**
     * Carrier Code
     *
     * @var string
     */
    const CARRIER_CODE = 'gfs';

    /**
     * Full Method Code saved against the quote/order entities
     */
    const METHOD_CODE = 'gfs_gfs';

    /**
     * Carrier Code
     *
     * @var string
     */
    protected $_code = self::CARRIER_CODE;

    /**
     * Rate Result Factory
     *
     * @var ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * Rate Method Factory
     *
     * @var MethodFactory
     */
    protected $_rateMethodFactory;

    /**
     * Gfs Config Helper
     *
     * @var Config
     */
    protected $_config;

    /**
     * App State
     *
     * @var State
     */
    protected $_appState;

    /**
     * Gfs Client
     *
     * @var Client
     */
    protected $_client;

    /**
     * Json
     *
     * @var Json
     */
    protected $_json;

    /**
     * Current Quote
     *
     * @var Quote|null
     */
    protected $_quote;

    /**
     * Gfs constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory         $rateErrorFactory
     * @param LoggerInterface      $logger
     * @param ResultFactory        $rateResultFactory
     * @param MethodFactory        $rateMethodFactory
     * @param Config               $config
     * @param State                $appState
     * @param Client               $client
     * @param Json                 $json
     * @param array                $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory         $rateErrorFactory,
        LoggerInterface      $logger,
        ResultFactory        $rateResultFactory,
        MethodFactory        $rateMethodFactory,
        Config               $config,
        State                $appState,
        Client               $client,
        Json                 $json,
        array                $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_config = $config;
        $this->_appState = $appState;
        $this->_client = $client;
        $this->_json = $json;
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $data
        );
    }

    /**
     * This method will collect the shipping rate for the GFS delivery method. If the customer has selected a
     * shipping method from the GFS checkout widget, the price and shipping details will be updated on the delivery
     * method so that it will be reflected in the totals.
     *
     * @param RateRequest $request
     *
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->_isMethodAvailable($request)) {
            return false;
        }

        $quote = $this->_getQuote($request);
        $price = $this->_getPrice($quote);
        $methodTitle = $this->_getMethodTitle($quote);

        /** @var Result $result */
        $result = $this->_rateResultFactory->create();
        /** @var Method $method */
        $method = $this->_rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->_config->getMethodTitle());
        $method->setMethod($this->_code);
        $method->setMethodTitle($methodTitle);
        $method->setPrice($price);
        $method->setCost($price);

        $result->append($method);

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return [
            self::CARRIER_CODE => $this->getConfigData('name')
        ];
    }

    /**
     * This method will check if the GFS shipping method is available. It will also set an checkout session with
     * the access token from gfs.
     *
     * @param RateRequest $request
     *
     * @return bool
     */
    protected function _isMethodAvailable(RateRequest $request)
    {
        if (!$this->_config->isActive()) {
            return false;
        }

        if (!$request->getDestPostcode()) {
            return false;
        }

        if (!$countryId = $request->getDestCountryId()) {
            return false;
        }

        if (!in_array($countryId, $this->_config->getAllowedCountries())) {
            return false;
        }

        if (!$this->_config->getRetailerId()) {
            return false;
        }

        if (!$this->_config->getRetailerSecret()) {
            return false;
        }

        if (!$this->_client->getAccessToken()) {
            return false;
        }

        return true;
    }

    /**
     * This method will get the quote from the rate request
     *
     * @param RateRequest $request
     *
     * @return Quote|null
     */
    protected function _getQuote(RateRequest $request)
    {
        if (!$this->_quote) {
            /** @var Item[] $items */
            $items = $request->getAllItems();
            if ($items[0]) {
                $quote = $items[0]->getQuote();
                $this->_quote = $quote;
            }
        }

        return $this->_quote;
    }

    /**
     * This method will attempt to get the delivery price of the selected shipping method
     *
     * @param Quote $quote
     *
     * @return float
     */
    protected function _getPrice($quote)
    {
        $price = $this->_config->getDefaultPrice();
        $data = $this->_getGfsData($quote);
        if (empty($data)) {
            return $price;
        }

        if (isset($data['data']['shipping']['price'])) {
            $price = (float) $data['data']['shipping']['price'];
        }

        return $price;
    }

    /**
     * This method will generate the title for the shipping method
     *
     * @param Quote $quote
     *
     * @return string
     */
    protected function _getMethodTitle($quote)
    {
        $methodTitle = $this->_config->getMethodTitle();
        $data = $this->_getGfsData($quote);
        if (empty($data)) {
            return $methodTitle;
        }

        switch ($data['shippingMethodType']) {
            case 'standard':
                $standardMethodTitle = $this->_getMethodTitleStandard($data);
                $methodTitle = $standardMethodTitle ? $standardMethodTitle : $methodTitle;
                break;
            case 'calendar':
                $calendarMethodTitle = $this->_getMethodTitleCalendar($data);
                $methodTitle = $calendarMethodTitle ? $calendarMethodTitle : $methodTitle;
                break;
            case 'droppoint':
                $dropPointMethodTitle = $this->_getMethodTitleDropPoint($data);
                $methodTitle = $dropPointMethodTitle ? $dropPointMethodTitle : $methodTitle;
                break;
            default:
        }

        return $methodTitle;
    }

    /**
     * This method will get the shipping method title for standard delivery addresses
     *
     * @param array $data
     *
     * @return string
     */
    protected function _getMethodTitleStandard($data)
    {
        $methodTitleSegments = [];
        if (isset($data['data']['service']) && trim($data['data']['service']) !== '') {
            $methodTitleSegments[] = trim($data['data']['service']);
        }

        if (isset($data['data']['expDeliveryDateStart']) && isset($data['data']['expDeliveryDateEnd'])) {
            $deliveryDateStart = $this->_config->getGfsDate($data['data']['expDeliveryDateStart']);
            $deliveryDateEnd = $this->_config->getGfsDate($data['data']['expDeliveryDateEnd']);
            $methodTitleSegments[] = sprintf('%s - %s',
                $deliveryDateStart->format('d/m/Y g:sa'),
                $deliveryDateEnd->format('g:sa'));
        }

        return implode(', ', $methodTitleSegments);
    }

    /**
     * This method will get the shipping method title for calendar (nominated) deliveries including the delivery date.
     *
     * @param array $data
     *
     * @return string
     */
    protected function _getMethodTitleCalendar($data)
    {
        $methodTitleSegments = [];
        if (isset($data['data']['service']) && trim($data['data']['service']) !== '') {
            $methodTitleSegments[] = trim($data['data']['service']);
        }

        if (isset($data['data']['expDeliveryDateStart']) && isset($data['data']['expDeliveryDateEnd'])) {
            $deliveryDateStart = $this->_config->getGfsDate($data['data']['expDeliveryDateStart']);
            $deliveryDateEnd = $this->_config->getGfsDate($data['data']['expDeliveryDateEnd']);
            $methodTitleSegments[] = sprintf('%s - %s',
                $deliveryDateStart->format('d/m/Y g:sa'),
                $deliveryDateEnd->format('g:sa'));
        }

        return implode(', ', $methodTitleSegments);
    }

    /**
     * This method will get the shipping method title for drop points in GFS including the pickup address for
     * the deliveries.
     *
     * @param array $data
     *
     * @return string
     */
    protected function _getMethodTitleDropPoint($data)
    {
        $methodTitleSegments = [];
        if (isset($data['data']['service']) && trim($data['data']['service']) !== '') {
            $methodTitleSegments[] = trim($data['data']['service']);
        }

        if (isset($data['data']['expDeliveryDateStart']) && isset($data['data']['expDeliveryDateEnd'])) {
            $deliveryDateStart = $this->_config->getGfsDate($data['data']['expDeliveryDateStart']);
            $deliveryDateEnd = $this->_config->getGfsDate($data['data']['expDeliveryDateEnd']);
            $methodTitleSegments[] = sprintf('%s - %s',
                $deliveryDateStart->format('d/m/Y g:sa'),
                $deliveryDateEnd->format('g:sa'));
        }

        $methodTitle = implode(', ', $methodTitleSegments);

        if (isset($data['data']['deliveryAddress'])) {
            $methodTitle .= ' - ' . implode(', ', array_filter($data['data']['deliveryAddress']));
        }

        return $methodTitle;
    }

    /**
     * This method will attempt to extract the details of the shipping method that has been selected in GFS
     *
     * @param Quote $quote
     *
     * @return array
     */
    protected function _getGfsData($quote)
    {
        try {
            if (!$quote instanceof Quote) {
                throw new \Exception();
            }

            $gfsShippingData = $quote->getData('gfs_shipping_data');
            if (!$gfsShippingData) {
                throw new \Exception();
            }

            return $this->_json->unserialize($gfsShippingData);
        } catch (\Exception $e) {
            return [];
        }
    }
}
