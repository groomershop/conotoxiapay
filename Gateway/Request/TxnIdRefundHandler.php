<?php

namespace Conotoxia\Pay\Gateway\Request;

use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Api\Messages;
use Conotoxia\Pay\Helper\OrderHelper;
use Magento\Payment\Gateway\Response\HandlerInterface;
use InvalidArgumentException;
use Magento\Sales\Model\Order\Payment;

/**
 * Class TxnIdRefundHandler
 *
 * @package Conotoxia\Pay\Gateway\Request
 */
class TxnIdRefundHandler implements HandlerInterface
{
    /**
     * Handle response from payment gateway
     *
     * @param array $handlingSubject
     * @param array $response
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentData = OrderHelper::getPaymentData($handlingSubject);
        /** @var $payment Payment */
        $payment = $paymentData->getPayment();

        if (empty($response)) {
            throw new InvalidArgumentException('Could not add refund identifier to history.');
        }

        $payment->getOrder()->addCommentToStatusHistory(__(Messages::REFUND_CREATED, $response[Definitions::REFUND_ID]));
    }
}
