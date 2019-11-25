<?php

namespace JustShout\Gfs\Plugin\Quote\Model;

use JustShout\Gfs\Model\Gfs\Cookie;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\ShipmentEstimationInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote\Address;

/**
 * Gfs Shipping Method Management Plugin
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class ShippingMethodManagementPlugin
{
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
     * ShippingMethodManagementPlugin constructor
     *
     * @param Cookie\Address $addressCookie
     * @param Json           $json
     */
    public function __construct(
        Cookie\Address $addressCookie,
        Json           $json
    ) {
        $this->_addressCookie = $addressCookie;
        $this->_json = $json;
    }

    /**
     * This method will set the shipping address in a session for GFS to use. When using the checkout, the
     * shipping address is saved after you select your chosen shipping method and click the next button. This
     * method will ensure that if a customer changes address i.e. the postcode, the session will be updated for
     * the GFS widgets.
     *
     * @param ShipmentEstimationInterface $subject
     * @param int                         $cartId
     * @param AddressInterface|Address    $address
     *
     * @return array
     */
    public function beforeEstimateByExtendedAddress(
        ShipmentEstimationInterface $subject,
        $cartId,
        AddressInterface $address
    ) {
        $sessionAddress = $this->_json->serialize($address->getData());
        $this->_addressCookie->set($sessionAddress);

        return [
            $cartId,
            $address
        ];
    }
}
