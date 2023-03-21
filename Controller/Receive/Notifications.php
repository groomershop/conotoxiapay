<?php

namespace Conotoxia\Pay\Controller\Receive;

use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Exception\PaymentNotificationException;
use CKPL\Pay\Exception\RefundNotificationException;
use CKPL\Pay\Notification\NotificationInterface;
use CKPL\Pay\Notification\Payment\PaymentNotificationInterface;
use CKPL\Pay\Notification\Refund\RefundNotificationInterface;
use CKPL\Pay\Pay;
use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Api\Messages;
use Conotoxia\Pay\Helper\OrderHelper;
use Conotoxia\Pay\Logger\AsyncLogger;
use Conotoxia\Pay\Model\Configuration;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment;
use Magento\Sales\Model\Order\Payment\Transaction;
use Throwable;

/**
 * Class Notifications
 */
class Notifications implements CsrfAwareActionInterface
{
    /**
     * @var Configuration
     */
    private Configuration $config;

    /**
     * @var OrderHelper
     */
    private OrderHelper $orderHelper;

    /**
     * @var AsyncLogger
     */
    private AsyncLogger $logger;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var TransactionRepositoryInterface
     */
    private TransactionRepositoryInterface $transactionRepository;

    /**
     * @var OrderPaymentRepositoryInterface
     */
    private OrderPaymentRepositoryInterface $paymentRepository;

    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    /**
     * Notification constructor.
     *
     * @param Configuration $config
     * @param OrderHelper $orderHelper
     * @param AsyncLogger $logger
     * @param OrderRepositoryInterface $orderRepository
     * @param TransactionRepositoryInterface $transactionRepository
     * @param OrderPaymentRepositoryInterface $paymentRepository
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        Configuration $config,
        OrderHelper $orderHelper,
        AsyncLogger $logger,
        OrderRepositoryInterface $orderRepository,
        TransactionRepositoryInterface $transactionRepository,
        OrderPaymentRepositoryInterface $paymentRepository,
        ResultFactory $resultFactory)
    {
        $this->config = $config;
        $this->orderHelper = $orderHelper;
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->transactionRepository = $transactionRepository;
        $this->paymentRepository = $paymentRepository;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $sdk = $this->getPaySDK();
                $rawBody = $this->getRawBody();
                $notification = $this->getNotification($sdk, $rawBody);

                if ($notification->isPaymentNotification()) {
                    $this->processIncomingPaymentNotification($notification);
                    $result->setHttpResponseCode(Http::STATUS_CODE_200);
                } elseif ($notification->isRefundNotification()) {
                    $this->processIncomingRefundNotification($notification);
                    $result->setHttpResponseCode(Http::STATUS_CODE_200);
                } else {
                    $result->setHttpResponseCode(Exception::HTTP_BAD_REQUEST);
                }
            } else {
                $result->setHttpResponseCode(Exception::HTTP_METHOD_NOT_ALLOWED);
            }
        } catch (Throwable $e) {
            $this->logger->warning($e->getMessage(), [$e->getTraceAsString()]);
            $result->setHttpResponseCode(Exception::HTTP_BAD_REQUEST);
        }

        return $result;
    }

    /**
     * @param RequestInterface $request
     *
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @param RequestInterface $request
     *
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * @return Pay
     * @throws \Exception
     */
    protected function getPaySDK(): Pay
    {
        return $this->config->initializeConotoxiaPay();
    }

    /**
     * @param Pay $sdk
     * @param string $rawBody
     *
     * @return NotificationInterface
     * @throws PayloadException
     * @throws PaymentNotificationException
     * @throws RefundNotificationException
     */
    protected function getNotification(Pay $sdk, string $rawBody): NotificationInterface
    {
        return $sdk->notification()->getNotification($rawBody);
    }

    /**
     * @return string
     */
    private function getRawBody(): string
    {
        $body = file_get_contents('php://input');
        if (strlen(trim($body)) > 0) {
            return trim($body);
        }
        return '';
    }

    /**
     * @param PaymentNotificationInterface $notification
     *
     * @throws \Exception
     */
    private function processIncomingPaymentNotification(PaymentNotificationInterface $notification)
    {
        $order = $this->getOrder($notification);
        $payment = $this->getPayment($order);

        if ($notification->isCompleted()) {
            $payment->setIsTransactionPending(false);
            $payment->accept();
            $phrase = $this->preparePhrase(Messages::PAYMENT_COMPLETED, $notification->getPaymentId());
            OrderHelper::setOrderStatus($order, Order::STATE_PROCESSING, $phrase);
        } elseif ($notification->isRejected() || $notification->isCancelled()) {
            $payment->cancel();
            $phrase = $this->preparePhrase(Messages::PAYMENT_CANCELLED, $notification->getPaymentId());
            $order->addCommentToStatusHistory($phrase);
        }

        $this->updateOrder($order, $notification->getStatus());
    }

    /**
     * @param PaymentNotificationInterface|RefundNotificationInterface $notification
     *
     * @return OrderInterface
     * @throws NotFoundException
     */
    private function getOrder(NotificationInterface $notification): OrderInterface
    {
        $externalPaymentId = $notification->getExternalPaymentId();
        $order = $this->orderHelper->getOrderByIncrementId($externalPaymentId);
        if (!$order) {
            throw new NotFoundException(__('Order with identifier ' . $externalPaymentId . ' not found'));
        }
        return $order;
    }

    /**
     * @param OrderInterface $order
     *
     * @return OrderPaymentInterface
     */
    private function getPayment(OrderInterface $order): OrderPaymentInterface
    {
        return $order->getPayment();
    }

    /**
     * @param OrderInterface $order
     * @param string $status
     *
     * @throws LocalizedException
     */
    private function updateOrder(OrderInterface $order, string $status)
    {
        /** @var Payment $payment */
        $payment = $order->getPayment();

        /** @var Transaction $transaction */
        $transaction = $payment->getAuthorizationTransaction();
        $transaction->setAdditionalInformation(Transaction::RAW_DETAILS, [Definitions::TRANSACTION_STATUS => $status]);

        $this->paymentRepository->save($payment);
        $this->transactionRepository->save($transaction);
        $this->orderRepository->save($order);
    }

    /**
     * @param RefundNotificationInterface $notification
     *
     * @throws \Exception
     */
    private function processIncomingRefundNotification(RefundNotificationInterface $notification)
    {
        $order = $this->getOrder($notification);
        if ($notification->isCompleted()) {
            $phrase = $this->preparePhrase(Messages::REFUND_COMPLETED, $notification->getRefundId());
            $order->addCommentToStatusHistory($phrase);
            $this->orderRepository->save($order);
        } elseif ($notification->isProcessing()) {
            $phrase = $this->preparePhrase(Messages::REFUND_PROCESSING, $notification->getRefundId());
            $order->addCommentToStatusHistory($phrase);
            $this->orderRepository->save($order);
        } elseif ($notification->isPending()) {
            $phrase = $this->preparePhrase(Messages::REFUND_PENDING, $notification->getRefundId());
            $order->addCommentToStatusHistory($phrase);
            $this->orderRepository->save($order);
        } elseif ($notification->isCancelled()) {
            $phrase = $this->preparePhrase(Messages::REFUND_CANCELLED, $notification->getRefundId());
            $order->addCommentToStatusHistory($phrase);
            $this->orderRepository->save($order);
        }
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

