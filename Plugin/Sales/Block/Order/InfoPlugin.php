<?php

namespace JustShout\Gfs\Plugin\Sales\Block\Order;

use JustShout\Gfs\Block\Order\Info\Gfs;
use JustShout\Gfs\Model\Carrier\Gfs as GfsCarrier;
use Magento\Sales\Block\Order\Info;
use Magento\Sales\Model\Order;

/**
 * Order Info Plugin
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class InfoPlugin
{
    /**
     * This plugin will set the shipping description block html if the shipping method is GFS.
     *
     * @param Info  $subject
     * @param Order $order
     *
     * @return Order
     */
    public function afterGetOrder(Info $subject, $order)
    {
        if ($order->getShippingMethod() === GfsCarrier::METHOD_CODE) {
            /** @var Gfs $gfsBlock */
            $gfsBlock = $subject->getLayout()->createBlock(Gfs::class);
            $gfsBlock->setOrder($order);
            $order->setShippingDescription($gfsBlock->toHtml());
        }

        return $order;
    }

    /**
     * Overriding method as its only called on the shipping description on the order/info.phtml template, and this
     * also avoids transferring the order/info.phtml to the gfs module.
     *
     * @param Info     $subject
     * @param \Closure $proceed
     * @param string   $data
     *
     * @return string
     */
    public function aroundEscapeHtml($subject, \Closure $proceed, $data)
    {
        return $data;
    }
}
