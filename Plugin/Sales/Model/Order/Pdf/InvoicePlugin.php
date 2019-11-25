<?php

namespace JustShout\Gfs\Plugin\Sales\Model\Order\Pdf;

use JustShout\Gfs\Helper\Pdf as PdfHelper;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Pdf;

/**
 * Invoice Pdf Plugin
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class InvoicePlugin
{
    /**
     * GFS Pdf Helper
     *
     * @var PdfHelper
     */
    protected $_pdfHelper;

    /**
     * InvoicePlugin constructor
     *
     * @param PdfHelper $pdfHelper
     */
    public function __construct(
        PdfHelper $pdfHelper
    ) {
        $this->_pdfHelper = $pdfHelper;
    }

    /**
     * This method will update the order object in the invoices so that the gfs shipping details are included
     *
     * @param Pdf\Invoice $subject
     * @param Invoice[]   $invoices
     *
     * @return array
     */
    public function beforeGetPdf(Pdf\Invoice $subject, $invoices = [])
    {
        foreach ($invoices as $invoice) {
            $order = $invoice->getOrder();
            $order->setShippingDescription($this->_pdfHelper->getPdfShippingDescription($order));
            $invoice->setOrder($order);
        }

        return [
            $invoices
        ];
    }
}
