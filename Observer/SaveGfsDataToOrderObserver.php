<?php

namespace JustShout\Gfs\Observer;

use JustShout\Gfs\Logger\Logger;
use JustShout\Gfs\Model\Gfs\Client;
use JustShout\Gfs\Model\Gfs\Cookie;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

/**
 * Order Save Observer
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class SaveGfsDataToOrderObserver implements ObserverInterface
{
    /**
     * Quote Repository
     *
     * @var CartRepositoryInterface
     */
    protected $_quoteRepository;

    /**
     * Gfs Client
     *
     * @var Client
     */
    protected $_client;

    /**
     * Gfs Logger
     *
     * @var Logger
     */
    protected $_logger;

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
     * SaveGfsDataToOrderObserver constructor
     *
     * @param CartRepositoryInterface $quoteRepository
     * @param Client                  $client
     * @param Logger                  $logger
     * @param Json                    $json
     * @param Cookie\Address          $addressCookie
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        Client                  $client,
        Logger                  $logger,
        Json                    $json,
        Cookie\Address          $addressCookie
    ) {
        $this->_quoteRepository = $quoteRepository;
        $this->_client = $client;
        $this->_logger = $logger;
        $this->_json = $json;
        $this->_addressCookie = $addressCookie;
    }

    /**
     * This method will save the gfs data from the quote entity to the order entity when the customer places the
     * order. This method will also run a request to close checkout endpoint via the GFS api, and will save the
     * json response against the order entity so that it can be communated to the customer via the account, emails
     * and pdfs. If a drop point method is being used and additional call will be made to get the points details.
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getOrder();
        /** @var Quote $quote */
        $quote = $this->_quoteRepository->get($order->getQuoteId());
        $order->setGfsShippingData($quote->getGfsShippingData());
        $order->setGfsCheckoutResult($quote->getGfsCheckoutResult());
        $order->setGfsSessionId($quote->getGfsSessionId());

        try {
            $shippingData = $this->_json->unserialize($quote->getGfsShippingData());
            if (isset($shippingData['data']['collectionPoint'])) {
                $dropPointId = $shippingData['data']['collectionPoint'];
                $dropPointDetails = $this->_client->getDropPointDetails($dropPointId);
                $dropPointDetails = $this->_json->serialize($dropPointDetails);
                $order->setGfsDropPointDetails($dropPointDetails);
            }
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }

        try {
            if (!$quote->getGfsSessionId()) {
                throw new \Exception('No gfs session id for order: ' . $order->getId());
            }
            if (!$quote->getGfsCheckoutResult()) {
                throw new \Exception('No gfs checkout data for order: ' . $order->getId());
            }
            $data = $this->_client->closeCheckout($quote->getGfsSessionId(), $quote->getGfsCheckoutResult());
            $order->setGfsDespatchBy($this->_getDespatchByDate($data));
            $data = $this->_json->serialize($data);
            $order->setGfsCloseCheckout($data);
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }

        $this->_client->deleteAccessToken();
        $this->_addressCookie->delete();

        return $this;
    }

    /**
     * Get the despatch date from the close checkout response
     *
     * @param array $data
     *
     * @return string|null
     */
    protected function _getDespatchByDate($data)
    {
        if (!isset($data['despatchDate'])) {
            return null;
        }

        $date = new \DateTime($data['despatchDate']);

        return $date->format('Y-m-d H:i:s');
    }
}
