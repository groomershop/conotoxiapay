<?php

namespace Conotoxia\Pay\Gateway\Request;

use Conotoxia\Pay\Helper\OrderHelper;
use LogicException;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

/**
 * Class CaptureRequest
 *
 * @package Conotoxia\Pay\Gateway\Request
 */
class CaptureRequest implements BuilderInterface
{
    private ConfigInterface $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     *
     * @return array
     */
    public function build(array $buildSubject): array
    {
        $paymentData = OrderHelper::getPaymentData($buildSubject);
        $payment = $paymentData->getPayment();

        if (!$payment instanceof OrderPaymentInterface) {
            throw new LogicException('Order payment should be provided.');
        }

        $payment->setIsTransactionClosed(true);

        return [];
    }
}
