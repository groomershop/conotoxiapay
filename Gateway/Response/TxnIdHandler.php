<?php

namespace Conotoxia\Pay\Gateway\Response;

use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Api\Messages;
use Conotoxia\Pay\Helper\OrderHelper;
use InvalidArgumentException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment;

/**
 * Class TxnIdHandler
 * @package Conotoxia\Pay\Gateway\Response
 */
class TxnIdHandler implements HandlerInterface
{
    /**
     * Handles transaction id
     *
     * @param array $handlingSubject
     * @param array $response
     *
     * @return void
     * @throws LocalizedException
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentData = OrderHelper::getPaymentData($handlingSubject);
        $payment = $paymentData->getPayment();

        if (empty($response)) {
            throw new InvalidArgumentException('Could not create payment. Try again later.');
        }

        $approveUrl = $response[Definitions::APPROVE_URL];
        $paymentId = $response[Definitions::PAYMENT_ID];

        /** @var $payment Payment */
        $payment->setTransactionId($paymentId);
        $payment->setIsTransactionClosed(false);
        $payment->setIsTransactionPending(true);
        $payment->setAdditionalInformation([Definitions::APPROVE_URL => $approveUrl]);
        $phrase = $this->preparePhrase(Messages::PAYMENT_CREATED, $paymentId);

        OrderHelper::setOrderStatus($payment->getOrder(), Order::STATE_NEW, $phrase);
    }

    /**
     * @param string $message
     * @param string $id
     *
     * @return Phrase
     */
    private function preparePhrase(string $message, string $id): Phrase
    {
        return __($message, $id);
    }
}
