<?php

namespace JustShout\Gfs\Controller\Adminhtml\System;

use JustShout\Gfs\Logger\Logger;
use JustShout\Gfs\Model\Gfs\Client;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Test API Connection
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Connection extends Action
{
    /**
     * Gfs Acl
     */
    const GFS_RESOURCE = 'JustShout_Gfs::gfs';

    /**
     * Json Result Factory
     *
     * @var JsonFactory
     */
    protected $_jsonFactory;

    /**
     * Gfs Client
     *
     * @var Client
     */
    protected $_client;

    /**
     * Gfs Logger
     *
     * @var Logger
     */
    protected $_logger;

    /**
     * Connection constructor
     *
     * @param Action\Context $context
     * @param JsonFactory    $jsonFactory
     * @param Client         $client
     * @param Logger         $logger
     */
    public function __construct(
        Action\Context $context,
        JsonFactory    $jsonFactory,
        Client         $client,
        Logger         $logger
    ) {
        parent::__construct($context);
        $this->_jsonFactory = $jsonFactory;
        $this->_client = $client;
        $this->_logger = $logger;
    }

    /**
     * This method will check if the connection details are correct for GFS.
     *
     * @return Json
     */
    public function execute()
    {
        $result = $this->_jsonFactory->create();
        try {
            $accessToken = $this->_client->getAccessToken();
            if (!$accessToken) {
                throw new \Exception();
            }
            $data = [
                'message' => __('Connection to GFS has been successful'),
            ];
            $code = 200;
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
            $data = [
                'message' => __('There has been an error trying to connect to GFS. please check your logs'),
            ];
            $code = 401;
        }

        return $result->setData($data)
            ->setHttpResponseCode($code);
    }
}
