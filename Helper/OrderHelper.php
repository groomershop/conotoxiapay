<?php

namespace Conotoxia\Pay\Helper;

use Conotoxia\Pay\Api\Definitions;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Payment;

/**
 * Class OrderHelper
 * @package Conotoxia\Pay\Helper
 */
class OrderHelper
{
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @type string
     */
    private const PAYMENT = 'payment';

    /**
     * Config constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param OrderInterface $order
     * @param string $state
     * @param null|string $message
     */
    public static function setOrderStatus(OrderInterface $order, string $state, string $message = null): void
    {
        /** @var Payment $payment */
        $payment = $order->getPayment();

        $order->setState($state)->setStatus($order->getConfig()->getStateDefaultStatus($state));

        if (!empty($message)) {
            if (!empty($payment->getAuthorizationTransaction())) {
                $phrase = __($message, $payment->getAuthorizationTransaction()->getTxnId());
            } else {
                $phrase = __($message, $payment->getTransactionId());
            }

            $order->addCommentToStatusHistory(
                $phrase,
                $order->getConfig()->getStateDefaultStatus($state)
            );
        }
    }

    /**
     * @param array $data
     *
     * @return PaymentDataObjectInterface
     */
    public static function getPaymentData(array $data)
    {
        if (!isset($data[self::PAYMENT]) || !$data[self::PAYMENT] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }
        return $data[self::PAYMENT];
    }

    /**
     * @param $id
     *
     * @return OrderInterface|bool
     */
    public function getOrderByIncrementId($id)
    {
        $criteria = $this->searchCriteriaBuilder->addFilter(
            OrderInterface::INCREMENT_ID,
            $id
        )->create();

        return current($this->orderRepository->getList($criteria)->getItems());
    }
}
