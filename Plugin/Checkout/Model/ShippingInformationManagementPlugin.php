<?php

namespace JustShout\Gfs\Plugin\Checkout\Model;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Quote\Model\QuoteRepository;

/**
 * Shipping Information Plugin
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class ShippingInformationManagementPlugin
{
    /**
     * Quote Repository
     *
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * ShippingInformationManagementPlugin constructor
     *
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * This method will extract the gfs extension attributes from the save shipping information request and will
     * save them to the quote entity.
     *
     * @param ShippingInformationManagement $subject
     * @param int                           $cartId
     * @param ShippingInformationInterface  $addressInformation
     *
     * @return array
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $extensionAttributes = $addressInformation->getExtensionAttributes();
        $gfsShippingData = $extensionAttributes->getGfsShippingData();
        $gfsCheckoutResult = $extensionAttributes->getGfsCheckoutResult();
        $gfsSessionId = $extensionAttributes->getGfsSessionId();
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setGfsShippingData($gfsShippingData);
        $quote->setGfsCheckoutResult($gfsCheckoutResult);
        $quote->setGfsSessionId($gfsSessionId);

        return [
            $cartId,
            $addressInformation
        ];
    }

}
