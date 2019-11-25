<?php

namespace JustShout\Gfs\Observer;

use JustShout\Gfs\Logger\Logger;
use JustShout\Gfs\Model\Gfs\Cookie;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;

/**
 * Set Initial Address Observer
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class SetInitialAddressObserver implements ObserverInterface
{
    /**
     * @var CustomerSessionFactory
     */
    protected $_customerSessionFactory;

    /**
     * @var CheckoutSessionFactory
     */

    protected $_checkoutSessionFactory;
    /**
     * @var AccountManagementInterface
     */
    protected $_accountManagement;

    /**
     * @var Quote\AddressFactory
     */
    protected $_addressFactory;

    /**
     * @var CartRepositoryInterface
     */
    protected $_quoteRepository;

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
     * @var Logger
     */
    protected $_logger;

    /**
     * SetInitialAddressObserver constructor
     *
     * @param CustomerSessionFactory     $customerSessionFactory
     * @param CheckoutSessionFactory     $checkoutSessionFactory
     * @param AccountManagementInterface $accountManagement
     * @param Quote\AddressFactory       $addressFactory
     * @param CartRepositoryInterface    $quoteRepository
     * @param Json                       $json
     * @param Cookie\Address             $addressCookie
     * @param Logger                     $logger
     */
    public function __construct(
        CustomerSessionFactory     $customerSessionFactory,
        CheckoutSessionFactory     $checkoutSessionFactory,
        AccountManagementInterface $accountManagement,
        Quote\AddressFactory       $addressFactory,
        CartRepositoryInterface    $quoteRepository,
        Json                       $json,
        Cookie\Address             $addressCookie,
        Logger                     $logger
    ) {
        $this->_customerSessionFactory = $customerSessionFactory;
        $this->_checkoutSessionFactory = $checkoutSessionFactory;
        $this->_accountManagement = $accountManagement;
        $this->_addressFactory = $addressFactory;
        $this->_quoteRepository = $quoteRepository;
        $this->_json = $json;
        $this->_addressCookie = $addressCookie;
        $this->_logger = $logger;
    }

    /**
     * This method will set the initial address for the quote if its not set
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        try {
            $customer = $this->_getCustomer();
            if (!$customer->getId()) {
                return $this;
            }

            $quote = $this->_getQuote();
            if (!$quoteId = $quote->getId()) {
                return $this;
            }

            $shippingAddress = $quote->getShippingAddress();
            $postCode = $shippingAddress->getPostcode();
            if ($postCode) {
                $sessionAddress = $this->_json->serialize($shippingAddress->getData());
                $this->_addressCookie->set($sessionAddress);

                return $this;
            } else {
                $address = $this->_getDefaultAddress((int) $customer->getId());

                if ($address) {
                    $quote->setShippingAddress($address)
                        ->setBillingAddress($address)->save();
                    $this->_quoteRepository->save($quote);
                    /** @var Quote $updatedQuote */
                    $updatedQuote = $this->_quoteRepository->get((int) $quoteId);
                    $sessionAddress = $this->_json->serialize($updatedQuote->getShippingAddress()->getData());
                    $this->_addressCookie->set($sessionAddress);
                }
            }
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }

        return $this;
    }

    /**
     * Get Customer
     *
     * @return Customer
     */
    protected function _getCustomer()
    {
        /** @var CustomerSession $session */
        $session = $this->_customerSessionFactory->create();

        return $session->getCustomer();
    }

    /**
     * Get Quote
     *
     * @return Quote
     */
    protected function _getQuote()
    {
        /** @var CheckoutSession $session */
        $session = $this->_checkoutSessionFactory->create();

        return $session->getQuote();
    }

    /**
     * Get Default address
     *
     * @param int $customerId
     *
     * @return Quote\Address|null
     */
    protected function _getDefaultAddress($customerId)
    {
        try {
            $customerAddress = $this->_accountManagement->getDefaultShippingAddress($customerId);
            if (!$customerAddress) {
                throw new \Exception();
            }

            $address = $this->_addressFactory->create();
            $address->setCustomerAddressData($customerAddress);
            $address->setCustomerAddressId($customerAddress->getId());

            return $address;
        } catch (\Exception $e) {
            return null;
        }
    }
}
