<?php

namespace JustShout\Gfs\Plugin\Quote\Model\Quote;

use JustShout\Gfs\Helper\Config;
use Magento\Quote\Model\Quote\Address;

/**
 * Quote Address Plugin
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class AddressPlugin
{
    /**
     * Config Helper
     *
     * @var Config
     */
    protected $_configHelper;

    /**
     * AddressPlugin constructor
     *
     * @param Config $configHelper
     */
    public function __construct(
        Config $configHelper
    ) {
        $this->_configHelper = $configHelper;
    }

    /**
     * If the GFS shipping carrier is enabled, this method will ensure that even if other shipping carriers are
     * enabled, GFS will be the only one shown.
     *
     * @param Address        $subject
     * @param Address\Rate[] $results
     *
     * @return Address\Rate[]
     */
    public function afterGetGroupedAllShippingRates($subject, $results)
    {
        if (!$this->_configHelper->isActive()) {
            return $results;
        }

        foreach ($results as $key => $rate) {
            if ($key === 'gfs') {
                continue;
            }
            unset($results[$key]);
        }

        return $results;
    }
}
