<?php

namespace JustShout\Gfs\Helper;

use JustShout\Gfs\Model\Carrier\Gfs;
use Magento\Sales\Model\Order;

/**
 * Pdf Helper
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Pdf extends Data
{
    /**
     * This method will get a description for the shipping on pdfs
     *
     * @param Order $order
     *
     * @return null|string
     */
    public function getPdfShippingDescription(Order $order)
    {
        $description = $order->getShippingDescription();
        if ($order->getShippingMethod() !== Gfs::METHOD_CODE) {
            return $description;
        }

        $gfsShippingData = $this->getGfsShippingData($order);
        if (empty($gfsShippingData)) {
            return $description;
        }

        switch ($gfsShippingData['shippingMethodType']) {
            case 'standard':
            case 'calendar':
                $standard = $this->_getStandardDescription($order);
                if ($standard) {
                    $description = $standard;
                }
                break;
            case 'droppoint':
                $dropPoint = $this->_getDropPointDescription($order);
                if ($dropPoint) {
                    $description = $dropPoint;
                }
                break;
        }

        return $description;
    }

    /**
     * This method will add a standard delivery description to a pdf
     *
     * @param Order $order
     *
     * @return null|string
     */
    protected function _getStandardDescription(Order $order)
    {
        $data = $this->getGfsCloseCheckoutData($order);
        if (empty($data)) {
            return null;
        }

        $description = trim($data['selectedService']['methodTitle']);
        if (isset($data['selectedService']['deliveryTimeFrom'])) {
            $deliveryDate = $this->getGfsDate($data['selectedService']['deliveryTimeFrom']);
            $description .= ' - ' . $deliveryDate->format('d/m/Y');
        }

        return $description;
    }

    /**
     * This method will add a drop point delivery description to a pdf
     *
     * @param Order $order
     *
     * @return null|string
     */
    protected function _getDropPointDescription(Order $order)
    {
        $data = $this->getGfsCloseCheckoutData($order);
        if (empty($data)) {
            return null;
        }

        $description = $this->_getStandardDescription($order);

        $lines = [];
        if (isset($data['selectedDroppoint']['droppointDescription'])) {
            $lines[] = trim($data['selectedDroppoint']['droppointDescription']);
        }
        if (isset($data['selectedDroppoint']['geoLocation']['addressLines'])) {
            $lines[] = implode(', ', $data['selectedDroppoint']['geoLocation']['addressLines']);
        }
        if (isset($data['selectedDroppoint']['geoLocation']['county'])) {
            $lines[] = trim($data['selectedDroppoint']['geoLocation']['county']);
        }
        if (isset($data['selectedDroppoint']['geoLocation']['town'])) {
            $lines[] = trim($data['selectedDroppoint']['geoLocation']['town']);
        }
        if (isset($data['selectedDroppoint']['geoLocation']['postCode'])) {
            $lines[] = trim($data['selectedDroppoint']['geoLocation']['postCode']);
        }

        $description .= PHP_EOL . implode(PHP_EOL, $lines);

        return $description;
    }
}
