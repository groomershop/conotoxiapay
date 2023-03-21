<?php

namespace Conotoxia\Pay\Logger\Handler;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Class Handler
 * @package Conotoxia\Pay\Logger\Handler;
 */
class Client extends Base
{
    const FILENAME = '/var/log/ConotoxiaPay.log';

    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = self::FILENAME;
}
