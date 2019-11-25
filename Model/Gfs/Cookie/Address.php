<?php

namespace JustShout\Gfs\Model\Gfs\Cookie;

use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Gfs Address Cookie
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Address
{
    /**
     * Gfs Address
     */
    const GFS_ADDRESS_COOKIE = 'gfs_address';

    /**
     * CookieManager
     *
     * @var CookieManagerInterface
     */
    protected $_cookieManager;

    /**
     * Cookie Metadata Factory
     *
     * @var CookieMetadataFactory
     */
    protected $_cookieMetadataFactory;

    /**
     * Session Manager
     *
     * @var SessionManagerInterface
     */
    protected $_sessionManager;

    /**
     * Address
     *
     * @param CookieManagerInterface  $cookieManager
     * @param CookieMetadataFactory   $cookieMetadataFactory
     * @param SessionManagerInterface $sessionManager
     */
    public function __construct(
        CookieManagerInterface  $cookieManager,
        CookieMetadataFactory   $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    ) {
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager = $sessionManager;
    }

    /**
     * Get Access Token Cookie
     *
     * @return string
     */
    public function get()
    {
        return $this->_cookieManager->getCookie(self::GFS_ADDRESS_COOKIE);
    }

    /**
     * Set Access Token Cookie
     *
     * @param string $value
     *
     * @return void
     */
    public function set($value)
    {
        $metadata = $this->_cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration(3600)
            ->setPath($this->_sessionManager->getCookiePath())
            ->setDomain($this->_sessionManager->getCookieDomain());

        $this->_cookieManager->setPublicCookie(
            self::GFS_ADDRESS_COOKIE,
            $value,
            $metadata
        );
    }

    /**
     * Delete Access Token Cookie
     *
     * @return void
     */
    public function delete()
    {
        $this->_cookieManager->deleteCookie(self::GFS_ADDRESS_COOKIE,
            $this->_cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setPath($this->_sessionManager->getCookiePath())
                ->setDomain($this->_sessionManager->getCookieDomain())
        );
    }
}
