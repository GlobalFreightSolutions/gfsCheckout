<?php

namespace JustShout\Gfs\Observer;

use JustShout\Gfs\Model\Gfs\Cookie;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Purge Access Token Observer Observer
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class PurgeAccessTokenObserver implements ObserverInterface
{
    /**
     * Gfs Address Cookie
     *
     * @var Cookie\Address
     */
    protected $_addressCookie;

    /**
     * @var
     */
    protected $_scopeConfig;

    /**
     * SaveGfsDataToOrderObserver constructor
     *
     * @param Cookie\Address       $addressCookie
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Cookie\Address       $addressCookie,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_addressCookie = $addressCookie;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * This method will purge access tokens if the module is disabled
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $config = (int) $this->_scopeConfig->getValue('carriers/gfs/active');
        if ($config) {
            return $this;
        }

        try {
            $this->_addressCookie->delete();
        } catch (\Exception $e) {}

        return $this;
    }
}
