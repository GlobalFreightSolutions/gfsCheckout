<?php

namespace JustShout\Gfs\Block\Order\Info;

use JustShout\Gfs\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;

/**
 * Gfs Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Gfs extends Template
{
    /**
     * Order Entity
     *
     * @var Order
     */
    protected $_order;

    /**
     * GFS Helper
     *
     * @var Data
     */
    protected $_gfsHelper;

    /**
     * GFS Shipping Data
     *
     * @var array
     */
    protected $_gfsCloseCheckoutData;

    /**
     * GFS Drop Point Data
     *
     * @var array
     */
    protected $_gfsDropPointData;

    /**
     * Gfs constructor
     *
     * @param Template\Context $context
     * @param Data             $gfsHelper
     * @param array            $data
     */
    public function __construct(
        Template\Context $context,
        Data             $gfsHelper,
        array            $data = []
    ) {
        parent::__construct($context, $data);
        $this->_gfsHelper = $gfsHelper;
    }

    /**
     * Get Order
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Set Order
     *
     * @param Order $order
     *
     * @return $this
     */
    public function setOrder(Order $order)
    {
        $this->_order = $order;

        return $this;
    }

    /**
     * This method will get the data from the close checkout response stored against the order entity
     *
     * @return array
     */
    public function getGfsCloseCheckoutData()
    {
        if (!$this->_gfsCloseCheckoutData) {
            $this->_gfsCloseCheckoutData = $this->_gfsHelper->getGfsCloseCheckoutData($this->getOrder());
        }

        return $this->_gfsCloseCheckoutData;
    }

    /**
     * This method will get the data if a drop point has been used
     *
     * @return array
     */
    public function getGfsDropPointData()
    {
        if (!$this->_gfsDropPointData) {
            $this->_gfsDropPointData = $this->_gfsHelper->getGfsDropPointData($this->getOrder());
        }

        return $this->_gfsDropPointData;
    }

    /**
     * Get the service title
     *
     * @return string
     */
    public function getServiceTitle()
    {
        $data = $this->getGfsCloseCheckoutData();
        $service = trim($data['description']);
        if (isset($data['deliveryDate'])) {
            $deliveryDate = $this->_gfsHelper->getGfsDate($data['deliveryDate']);
            $service .= ' - ' . $deliveryDate->format('d/m/Y');
        }

        return $service;
    }

    /**
     * This method will get the address of the drop point delivery service
     *
     * @return string
     */
    public function getDropPointAddress()
    {
        $data = $this->getGfsDropPointData();
        $lines = [];
        if (isset($data['title'])) {
            $lines[] = trim($data['title']);
        }
        if (isset($data['address'])) {
            $lines[] = implode(', ', $data['address']);
        }
        if (isset($data['town'])) {
            $lines[] = trim($data['town']);
        }
        if (isset($data['zip'])) {
            $lines[] = trim($data['zip']);
        }

        return implode('<br/>', $lines);
    }

    /**
     * Get the delivery times for drop points
     *
     * @return array
     */
    public function getDropPointOpeningTimes()
    {
        $data = $this->getGfsDropPointData();
        $times = [];
        if (!isset($data['days'])) {
            return $times;
        }

        for ($d = 0; $d < 7; $d++) {
            if (!isset($data['days'][$d])) {
                continue;
            }
            $day = $data['days'][$d];
            $dayName = $this->_gfsHelper->getGfsDate($day['dayOfWeek']);
            $times[$dayName->format('N')] = [
                'day'  => $dayName->format('D'),
                'from' => $day['slots'][0]['from'],
                'to'   => $day['slots'][0]['to']
            ];
        }

        ksort($times);

        return $times;
    }

    /**
     * Get the GFS Logo
     *
     * @return null|string
     */
    public function getGfsLogo()
    {
        $currentUrl = $this->_storeManager->getStore()->getCurrentUrl(false);
        $logo = $this->getViewFileUrl('JustShout_Gfs::images/logo.png');
        if (strpos($currentUrl, 'print') !== false) {
            $logo = null;
        }

        return $logo;
    }

    /**
     * Set the block template
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->setTemplate($this->_getGfsTemplate());

        parent::_beforeToHtml();

        return $this;
    }

    /**
     * This method will decided which template will show the gfs shipping data
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
            $template = sprintf('JustShout_Gfs::order/info/gfs/%s.phtml', $gfsShippingData['shippingMethodType']);
        }

        return $template;
    }
}
