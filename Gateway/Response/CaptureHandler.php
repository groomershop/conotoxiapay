<?php

namespace Conotoxia\Pay\Gateway\Response;

use Magento\Payment\Gateway\Response\HandlerInterface;

/**
 * Class CaptureHandler
 * @package Conotoxia\Pay\Gateway\Response
 */
class CaptureHandler implements HandlerInterface
{
    /**
     * @param array $handlingSubject
     * @param array $response
     */
    public function handle(array $handlingSubject, array $response)
    {
    }
}
