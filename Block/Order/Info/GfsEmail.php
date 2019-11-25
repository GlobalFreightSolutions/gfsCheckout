<?php

namespace JustShout\Gfs\Block\Order\Info;

/**
 * Gfs Email Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class GfsEmail extends Gfs
{
    /**
     * Force the area to frontend
     *
     * @return string
     */
    public function getArea()
    {
        return 'frontend';
    }

    /**
     * {@inheritdoc}
     *
     * @return null|string
     */
    public function getGfsLogo()
    {
        $currentUrl = $this->_storeManager->getStore()->getCurrentUrl(false);
        $logo = $this->getViewFileUrl('JustShout_Gfs::images/logo.png', [
            'area'  => 'frontend',
            'theme' => 'Magento/Luma'
        ]);
        if (strpos($currentUrl, 'print') !== false) {
            $logo = null;
        }

        return $logo;
    }

    /**
     * {@inheritdoc}
     *
     * Override the parent method so that the email specific templates are being used.
     *
     * @return null|string
     */
    protected function _getGfsTemplate()
    {
        $template = null;
        $order = $this->getOrder();
        $gfsShippingData = $this->_gfsHelper->getGfsShippingData($order);
        $gfsCloseCheckoutData = $this->getGfsCloseCheckoutData();
        if (empty($gfsShippingData) || empty($gfsCloseCheckoutData)) {
            return $template;
        }

        if (isset($gfsShippingData['shippingMethodType'])) {
            $template = sprintf('JustShout_Gfs::order/info/gfs-email/%s.phtml', $gfsShippingData['shippingMethodType']);
        }

        return $template;
    }
}
