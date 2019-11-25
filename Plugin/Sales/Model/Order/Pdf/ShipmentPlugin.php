<?php

namespace JustShout\Gfs\Plugin\Sales\Model\Order\Pdf;

use JustShout\Gfs\Helper\Pdf as PdfHelper;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\Order\Pdf;

/**
 * Shipment Pdf Plugin
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class ShipmentPlugin
{
    /**
     * GFS Pdf Helper
     *
     * @var PdfHelper
     */
    protected $_pdfHelper;

    /**
     * ShipmentPlugin constructor
     *
     * @param PdfHelper $pdfHelper
     */
    public function __construct(
        PdfHelper $pdfHelper
    ) {
        $this->_pdfHelper = $pdfHelper;
    }

    /**
     * This method will update the order object in the shipments so that the gfs shipping details are included
     *
     * @param Pdf\Shipment $subject
     * @param Shipment[]   $shipments
     *
     * @return array
     */
    public function beforeGetPdf(Pdf\Shipment $subject, $shipments = [])
    {
        foreach ($shipments as $shipment) {
            $order = $shipment->getOrder();
            $order->setShippingDescription($this->_pdfHelper->getPdfShippingDescription($order));
            $shipment->setOrder($order);
        }

        return [
            $shipments
        ];
    }
}
