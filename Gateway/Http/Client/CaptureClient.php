<?php

namespace Conotoxia\Pay\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

/**
 * Class CaptureClient
 * @package Conotoxia\Pay\Gateway\Http\Client
 */
class CaptureClient implements ClientInterface
{
    /**
     * @param TransferInterface $transferObject
     *
     * @return array
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        return [];
    }
}
