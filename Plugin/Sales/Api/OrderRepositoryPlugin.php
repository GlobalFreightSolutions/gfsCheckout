<?php

namespace JustShout\Gfs\Plugin\Sales\Api;

use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Order Repository Plugin
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class OrderRepositoryPlugin
{
    /**
     * @var OrderExtensionFactory
     */
    protected $_orderExtensionFactory;

    /**
     * OrderRepositoryPlugin constructor
     *
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->_orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * Add GFS Data to Order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface           $order
     *
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        $closeCheckout = $order->getData('gfs_close_checkout');
        $despatchBy = $order->getData('gfs_despatch_by');

        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->_orderExtensionFactory->create();
        if (trim($closeCheckout)) {
            $extensionAttributes->setGfsCloseCheckout($closeCheckout);
        }
        if (trim($despatchBy)) {
            $extensionAttributes->setGfsDespatchBy($despatchBy);
        }

        $order->setExtensionAttributes($extensionAttributes);

        return $order;
    }

    /**
     * Add GFS Data to Orders
     *
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();
        foreach ($orders as &$order) {
            $closeCheckout = $order->getData('gfs_close_checkout');
            $despatchBy = $order->getData('gfs_despatch_by');

            /** @var OrderExtensionInterface $extensionAttributes */
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->_orderExtensionFactory->create();
            if (trim($closeCheckout)) {
                $extensionAttributes->setGfsCloseCheckout($closeCheckout);
            }
            if (trim($despatchBy)) {
                $extensionAttributes->setGfsDespatchBy($despatchBy);
            }

            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}
