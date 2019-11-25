<?php

namespace JustShout\Gfs\Block\Adminhtml\Order\View\Info;

use JustShout\Gfs\Helper\Data;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Magento\Sales\Helper\Admin;
use Magento\Sales\Model\Order;

/**
 * Gfs Admin Order Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Gfs extends AbstractOrder
{
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
     * Order Entity
     *
     * @var Order
     */
    protected $_order;

    /**
     * Gfs constructor
     *
     * @param Context  $context
     * @param Registry $registry
     * @param Admin    $adminHelper
     * @param Data     $gfsHelper
     * @param array    $data
     */
    public function __construct(
        Context  $context,
        Registry $registry,
        Admin    $adminHelper,
        Data     $gfsHelper,
        array    $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $adminHelper,
            $data
        );
        $this->_gfsHelper = $gfsHelper;
    }

    /**
     * Set Order Entity
     *
     * @param Order $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->_order = $order;

        return $this;
    }

    /**
     * Get Order Entity
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Get Shipping Amount
     *
     * @return string
     */
    public function getShippingAmount()
    {
        return $this->displayShippingPriceInclTax($this->getOrder());
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
     * Get the service name for standard deliveries
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
     * Get Drop Point Id
     */
    public function getDroppointId()
    {
        $id = null;
        $data = $this->getGfsCloseCheckoutData();
        if (isset($data['dropPoint'])) {
            $id = $data['dropPoint'];
        }

        return $id;
    }

    /**
     * Get the carrier for the delivery
     *
     * @return null|string
     */
    public function getCarrier()
    {
        $carrier = null;
        $data = $this->getGfsCloseCheckoutData();
        if (isset($data['carrier'])) {
            $carrier = $data['carrier'];
        }

        return $carrier;
    }

    /**
     * Get the service code for the delivery
     *
     * @return null|string
     */
    public function getServiceCode()
    {
        $carrier = null;
        $data = $this->getGfsCloseCheckoutData();
        if (isset($data['serviceCode'])) {
            $carrier = $data['serviceCode'];
        }

        return $carrier;
    }

    /**
     * Get the estimated delivery time for standard deliveries
     *
     * @return string
     */
    public function getServiceTime()
    {
        $data = $this->getGfsCloseCheckoutData();
        if (!isset($data['selectedService']['deliveryTimeFrom']) || !isset($data['selectedService']['deliveryTimeTo'])) {
            return null;
        }
        $deliveryTimeFrom = $this->_gfsHelper->getGfsDate($data['selectedService']['deliveryTimeFrom']);
        $deliveryTimeTo = $this->_gfsHelper->getGfsDate($data['selectedService']['deliveryTimeTo']);

        return __('<strong>Delivery Time:</strong> %1 - %2',
            $deliveryTimeFrom->format('g:sa'),
            $deliveryTimeTo->format('g:sa')
        );
    }

    /**
     * Get Despatch Date
     *
     * @return string
     */
    public function getDespatchDate()
    {
        $data = $this->getGfsCloseCheckoutData();
        if (!isset($data['despatchDate'])) {
            return null;
        }

        $dispatchDate = $this->_gfsHelper->getGfsDate($data['despatchDate']);

        return __('<strong>Despatch by:</strong> %1',
            $dispatchDate->format('d/m/Y')
        );
    }

    /**
     * This method will get the address of the drop point delivery service
     *
     * @return string
     */
    public function getDropPointServiceAddress()
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
            $template = sprintf('JustShout_Gfs::order/view/info/gfs/%s.phtml', $gfsShippingData['shippingMethodType']);
        }

        return $template;
    }
}
