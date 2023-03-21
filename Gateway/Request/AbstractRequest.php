<?php

namespace Conotoxia\Pay\Gateway\Request;

use Conotoxia\Pay\Helper\OrderHelper;
use LogicException;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

/**
 * Class AbstractRequest
 *
 * @package Conotoxia\Pay\Gateway\Request
 */
abstract class AbstractRequest
{
    /**
     * @var InfoInterface
     */
    protected InfoInterface $payment;

    /**
     * @var OrderAdapterInterface
     */
    protected OrderAdapterInterface $order;

    /**
     * @var PaymentDataObjectInterface
     */
    protected PaymentDataObjectInterface $buildPayment;

    public function build(array $buildSubject)
    {
        $this->buildPayment = OrderHelper::getPaymentData($buildSubject);
        $this->payment = $this->buildPayment->getPayment();
        $this->order = $this->buildPayment->getOrder();
        if (!$this->payment instanceof OrderPaymentInterface) {
            throw new LogicException('Order payment should be provided.');
        }
    }
}
