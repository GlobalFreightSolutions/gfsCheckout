<?php

namespace JustShout\Gfs\Block\Html;

use JustShout\Gfs\Helper\Config;
use JustShout\Gfs\Model\Gfs\Client;
use Magento\Directory\Model\Currency;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;

/**
 * Head Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Head extends Template
{
    /**
     * GFS Client
     *
     * @var Client
     */
    protected $_client;

    /**
     * GFS Config Helper
     *
     * @var Config
     */
    protected $_config;

    /**
     * @var Json
     */
    protected $_json;

    /**
     * Head constructor
     *
     * @param Template\Context $context
     * @param Client           $client
     * @param Config           $config
     * @param Json             $json
     * @param array            $data
     */
    public function __construct(
        Template\Context $context,
        Client           $client,
        Config           $config,
        Json             $json,
        array            $data = []
    ) {
        parent::__construct($context, $data);
        $this->_client = $client;
        $this->_config = $config;
        $this->_json = $json;
    }

    /**
     * Get Access Token
     *
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->_client->getAccessToken();
    }

    /**
     * Get Currency symbol
     *
     * @return string
     */
    public function getCurrencySymbol()
    {
        try {
            /** @var Currency $currency */
            $currency = $this->_storeManager->getStore()->getCurrentCurrency();
            $symbol = $currency->getCurrencySymbol();
        } catch (\Exception $e) {
            $currency = $this->_storeManager->getStore()->getDefaultCurrency();
            $symbol = $currency->getCurrencySymbol();
        }

        return $symbol;
    }

    /**
     * Get Enabled Delivery Types
     *
     * @return string
     */
    public function getDeliveryTypes()
    {
        return implode(',', $this->_config->getDeliveryTypes());
    }

    /**
     * Get Standard Delivery Title
     *
     * @return string
     */
    public function getStandardDeliveryTitle()
    {
        return $this->_config->getStandardDeliveryTitle();
    }

    /**
     * Get Calendar Delivery Title
     *
     * @return string
     */
    public function getCalendarDeliveryTitle()
    {
        return $this->_config->getCalendarDeliveryTitle();
    }

    /**
     * Get Drop Point Delivery Title
     *
     * @return string
     */
    public function getDropPointTitle()
    {
        return $this->_config->getDropPointTitle();
    }

    /**
     * Get Service Sort Order
     *
     * @return string
     */
    public function getServiceSortOrder()
    {
        return $this->_config->getServiceSortOrder();
    }

    /**
     * Get Map Api Key
     *
     * @return string
     */
    public function getMapApiKey()
    {
        return $this->_config->getMapApiKey();
    }

    /**
     * Get Map Home Icon
     *
     * @return string
     */
    public function getMapHomeIcon()
    {
        return $this->_config->getMapHomeIcon();
    }

    /**
     * Get Use Stores
     *
     * @return bool
     */
    public function getUseStores()
    {
        return $this->_config->getUseStores();
    }

    /**
     * Get Use DropPoint Stores
     *
     * @return bool
     */
    public function getUseDropPointStores()
    {
        return $this->_config->getUseDropPointStores();
    }

    /**
     * Get Use Standard
     *
     * @return string
     */
    public function getUseStandard()
    {
        return $this->_config->getUseStandard() ? 'true' : 'false';
    }

    /**
     * Get Use Drop Points
     *
     * @return string
     */
    public function getUseDropPoints()
    {
        return $this->_config->getUseDropPoints() ? 'true' : 'false';
    }

    /**
     * Get Use Calendar
     *
     * @return string
     */
    public function getUseCalendar()
    {
        return $this->_config->getUseCalendar() ? 'true' : 'false';
    }

    /**
     * Get Default Service
     *
     * @return string
     */
    public function getDefaultService()
    {
        return $this->_config->getDefaultService();
    }

    /**
     * Get Default Carrier
     *
     * @return string
     */
    public function getDefaultCarrier()
    {
        return $this->_config->getDefaultCarrier();
    }

    /**
     * Get Default Carrier Code
     *
     * @return string
     */
    public function getDefaultCarrierCode()
    {
        return $this->_config->getDefaultCarrierCode();
    }

    /**
     * Get Default Price
     *
     * @return string
     */
    public function getDefaultPrice()
    {
        /** @var Currency $currency */
        $currency = $this->_storeManager->getStore()->getCurrentCurrency();

        return $this->_json->serialize([
            'currency' => $currency->getCode(),
            'price'    => $this->_config->getDefaultPrice()
        ]);
    }

    /**
     * Get Default Min Delivery Time
     *
     * @return int
     */
    public function getDefaultMinDeliveryTime()
    {
        return $this->_config->getDefaultMinDeliveryTime();
    }

    /**
     * Get Default Max Delivery Time
     *
     * @return int
     */
    public function getDefaultMaxDeliveryTime()
    {
        return $this->_config->getDefaultMaxDeliveryTime();
    }

    /**
     * Get Primary Colour
     *
     * @return string
     */
    public function getColorPrimary()
    {
        return $this->_config->getColorPrimary();
    }

    /**
     * Get Secondary Colour
     *
     * @return string
     */
    public function getColorSecondary()
    {
        return $this->_config->getColorSecondary();
    }

    /**
     * Get Primary Colour
     *
     * @return string
     */
    public function getColorTertiary()
    {
        return $this->_config->getColorTertiary();
    }

    /**
     * Show Calendar No Service
     *
     * @return string
     */
    public function getShowCalendarNoService()
    {
        return $this->_config->getShowCalendarNoService() ? 'true' : 'false';
    }

    /**
     * Calendar No Service Message
     *
     * @return string
     */
    public function getCalendarNoService()
    {
        return $this->_config->getCalendarNoService();
    }

    /**
     * Get Day Labels
     *
     * @return string
     */
    public function getDayLabels()
    {
        $labels = $this->_config->getDayLabels();

        return '[' . implode(',', array_map(function($string) {
            return '"' . $string . '"';
        }, $labels)) . ']';
    }

    /**
     * Get Month Labels
     *
     * @return string
     */
    public function getMonthLabels()
    {
        $labels = $this->_config->getMonthLabels();

        return '[' . implode(',', array_map(function($string) {
            return '"' . $string . '"';
        }, $labels)) . ']';
    }

    /**
     * Get Disabled Dates
     *
     * @return string
     */
    public function getDisabledDates()
    {
        return '[' . implode(',', $this->_config->getDisabledDates()) . ']';
    }

    /**
     * Get Disabled Prev Days
     *
     * @return string
     */
    public function getDisabledPrevDays()
    {
        return $this->_config->getDisabledPrevDays() ? 'true' : 'false';
    }

    /**
     * Get Disabled Next Days
     *
     * @return string
     */
    public function getDisabledNextDays()
    {
        return $this->_config->getDisabledNextDays() ? 'true' : 'false';
    }

    /**
     * Get Gfs Logo Url
     *
     * @return string
     */
    public function getGfsLogoSrc()
    {
        return $this->getViewFileUrl('JustShout_Gfs::images/logo.png');
    }

    /**
     * Check GFS is active
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        if (!$this->_config->isActive() || !$this->getAccessToken()) {
            $this->setTemplate(null);
        }

        parent::_beforeToHtml();

        return $this;
    }
}
