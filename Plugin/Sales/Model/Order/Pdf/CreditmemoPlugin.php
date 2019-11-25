<?php

namespace JustShout\Gfs\Plugin\Sales\Model\Order\Pdf;

use JustShout\Gfs\Helper\Pdf as PdfHelper;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Pdf;

/**
 * CreditMemo Pdf Plugin
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class CreditmemoPlugin
{
    /**
     * GFS Pdf Helper
     *
     * @var PdfHelper
     */
    protected $_pdfHelper;

    /**
     * CreditmemoPlugin constructor
     *
     * @param PdfHelper $pdfHelper
     */
    public function __construct(
        PdfHelper $pdfHelper
    ) {
        $this->_pdfHelper = $pdfHelper;
    }

    /**
     * This method will update the order object in the credit memos so that the gfs shipping details are included
     *
     * @param Pdf\Creditmemo $subject
     * @param Creditmemo[]   $creditMemos
     *
     * @return array
     */
    public function beforeGetPdf(Pdf\Creditmemo $subject, $creditMemos = [])
    {
        foreach ($creditMemos as $creditMemo) {
            $order = $creditMemo->getOrder();
            $order->setShippingDescription($this->_pdfHelper->getPdfShippingDescription($order));
            $creditMemo->setOrder($order);
        }

        return [
            $creditMemos
        ];
    }
}
