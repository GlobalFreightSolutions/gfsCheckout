<?php

namespace JustShout\Gfs\Block\Checkout;

use JustShout\Gfs\Helper\Config;
use JustShout\Gfs\Model\Gfs\Client;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

/**
 * Gfs Layout Processor
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class GfsProcessor implements LayoutProcessorInterface
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
     * GfsProcessor constructor
     *
     * @param Client $client
     * @param Config $config
     */
    public function __construct(
        Client $client,
        Config $config
    ) {
        $this->_client = $client;
        $this->_config = $config;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout)
    {
        if (!$this->_isGfsActive()) {
            return $jsLayout;
        }

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['config']['shippingMethodListTemplate'] = 'JustShout_Gfs/shipping-address/shipping-method-list';
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['config']['shippingMethodItemTemplate'] = 'JustShout_Gfs/shipping-address/shipping-method-item';

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shippingAdditional']['component'] = 'uiComponent';
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shippingAdditional']['displayArea'] = 'shippingAdditional';
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shippingAdditional']['children']['gfs_carrier_form'] = [
            'component' => 'JustShout_Gfs/js/view/checkout/shipping/gfs'
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['step-config']['children']['shipping-rates-validation']['children']['gfs-rates-validation'] = [
            'component' => 'JustShout_Gfs/js/view/shipping-rates-validation'
        ];

        return $jsLayout;
    }

    /**
     * Check if GFS is Active
     *
     * @return bool
     */
    protected function _isGfsActive()
    {
        return $this->_config->isActive() && $this->_client->getAccessToken();
    }
}

