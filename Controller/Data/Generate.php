<?php

namespace JustShout\Gfs\Controller\Data;

use JustShout\Gfs\Logger\Logger;
use JustShout\Gfs\Model\Gfs\Request\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Gfs Checkout Widget Data Controller
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Generate extends Action
{
    /**
     * Gfs Data Model
     *
     * @var Data
     */
    protected $_data;

    /**
     * Json Result Factory
     *
     * @var JsonFactory
     */
    protected $_jsonFactory;

    /**
     * Gfs Logger
     *
     * @var Logger
     */
    protected $_logger;

    /**
     * Generate constructor
     *
     * @param Context     $context
     * @param JsonFactory $jsonFactory
     * @param Data        $data
     * @param Logger      $logger
     */
    public function __construct(
        Context     $context,
        JsonFactory $jsonFactory,
        Data        $data,
        Logger      $logger
    ) {
        parent::__construct($context);
        $this->_data = $data;
        $this->_jsonFactory = $jsonFactory;
        $this->_logger = $logger;
    }

    /**
     * This method will generate the json object used for the request data in the  checkout widget
     *
     * @return Json
     */
    public function execute()
    {
        $result = $this->_jsonFactory->create();
        try {
            $data = $this->_data->getGfsData();
            $initialAddress = $this->_data->getInitialAddress();
            $result = $this->_jsonFactory->create();
            $result->setData([
                'data'            => $data,
                'initial_address' => $initialAddress,
            ]);
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
            $result->setData([
                'data'            => null,
                'initial_address' => null,
            ]);
            $result->setStatusHeader(401);
        }

        return $result;
    }
}
