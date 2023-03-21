<?php

namespace Conotoxia\Pay\Gateway\Response;

use Conotoxia\Pay\Helper\OrderHelper;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;

/**
 * Class FraudHandler
 * @package Conotoxia\Pay\Gateway\Response
 */
class FraudHandler implements HandlerInterface
{
    const FRAUD_MSG_LIST = 'FRAUD_MSG_LIST';

    /**
     * Handles fraud messages
     *
     * @param array $handlingSubject
     * @param array $response
     *
     * @return void
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($response[self::FRAUD_MSG_LIST]) || !is_array($response[self::FRAUD_MSG_LIST])) {
            return;
        }

        $paymentData = OrderHelper::getPaymentData($handlingSubject);
        $payment = $paymentData->getPayment();

        $payment->setAdditionalInformation(
            self::FRAUD_MSG_LIST,
            (array) $response[self::FRAUD_MSG_LIST]
        );

        /** @var $payment Payment */
        $payment->setIsTransactionPending(true);
        $payment->setIsFraudDetected(true);
    }
}
