<?php

namespace JustShout\Gfs\Logger;

use Magento\Framework\Logger\Handler\Base;

/**
 * Gfs Logger Handler
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Handler extends Base
{
    /**
     * {@inheritdoc}
     *
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected $fileName = '/var/log/gfs.log';
}
