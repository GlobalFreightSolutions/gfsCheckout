<?php

namespace JustShout\Gfs\Observer;

use JustShout\Gfs\Block\Order\Info\GfsEmail;
use JustShout\Gfs\Model\Carrier\Gfs;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Model\Order;

/**
 * Set Shipping Description Order Email
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class SetOrderEmailShippingDescriptionObserver implements ObserverInterface
{
    /**
     * Json
     *
     * @var Json
     */
    protected $_json;

    /**
     * Layout
     *
     * @var LayoutInterface
     */
    protected $_layout;

    /**
     * SetOrderEmailShippingDescriptionObserver constructor.
     *
     * @param Json            $json
     * @param LayoutInterface $layout
     */
    public function __construct(
        Json            $json,
        LayoutInterface $layout
    ) {
        $this->_json = $json;
        $this->_layout = $layout;
    }

    /**
     * This method is called for the new order, invoice shipment and credit memo emails, and will update the shipping
     * description value of the order entity so that it will output the details from there gfs delivery, this has been
     * done because a client could have bespoke features on there email correspondents.
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order = $this->_getOrder($observer);
        if (!$order instanceof Order) {
            return $this;
        }

        if ($order->getShippingMethod() !== Gfs::METHOD_CODE) {
            return $this;
        }

        /** @var GfsEmail $gfsBlock */
        $gfsBlock = $this->_layout->createBlock(GfsEmail::class);
        $gfsBlock->setOrder($order);
        $order->setShippingDescription($gfsBlock->toHtml());

        $this->_setOrder($observer, $order);

        return $this;
    }

    /**
     * This method will get the order entity from the transport object/array
     *
     * @param Observer $observer
     *
     * @return Order|null
     */
    protected function _getOrder(Observer $observer)
    {
        $transport = $observer->getTransport();
        if (is_array($transport)) {
            $order = isset($transport['order']) ? $transport['order'] : null;
        } else {
            $order = $observer->getTransport()->getOrder();
        }

        return $order;
    }

    /**
     * This method will update the order object in the transport object so the gfs block is present
     *
     * @param Observer $observer
     * @param Order    $order
     *
     * @return $this
     */
    protected function _setOrder(Observer $observer, $order)
    {
        $transport = $observer->getTransport();
        if (is_array($transport)) {
            $observer->getTransport()['order'] = $order;
        } else {
            $observer->getTransport()->setOrder($order);
        }

        return $this;
    }
}
