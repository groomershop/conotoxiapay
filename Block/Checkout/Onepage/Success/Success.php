<?php

namespace Conotoxia\Pay\Block\Checkout\Onepage\Success;

use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Api\PaymentResults;
use Conotoxia\Pay\Helper\OrderHelper;
use Conotoxia\Pay\Logger\Logger;
use Conotoxia\Pay\Model\Configuration;
use Conotoxia\Pay\Model\Ui\ConfigProvider;
use Exception;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Payment;

/**
 * Class Success
 * @package Conotoxia\Pay\Block\Checkout\Onepage\Success
 */
class Success extends Template
{
    /**
     * @var CheckoutSession
     */
    protected CheckoutSession $checkoutSession;

    /**
     * @var CustomerSession
     */
    protected CustomerSession $customerSession;

    /**
     * @var OrderHelper
     */
    private OrderHelper $orderHelper;

    /**
     * @var Configuration
     */
    private Configuration $config;

    /**
     * @var HttpRequest
     */
    private HttpRequest $request;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var string
     */
    private string $paymentId;

    /**
     * @var string
     */
    private string $paymentResult;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var null|string
     */
    private ?string $urlData;

    /**
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param CustomerSession $customerSession
     * @param OrderHelper $orderHelper
     * @param Configuration $config
     * @param HttpRequest $request
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Logger $logger
     * @param array $data
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        OrderHelper $orderHelper,
        Configuration $config,
        HttpRequest $request,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Logger $logger,
        array $data = []
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->orderHelper = $orderHelper;
        $this->config = $config;
        $this->request = $request;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * @param Payment[] $payments
     * @param string $paymentId
     *
     * @return bool
     */
    public static function isValidOrder(array $payments, string $paymentId): bool
    {
        if ($payments === null || empty($payments)) {
            return false;
        }

        foreach ($payments as $payment) {
            if ($payment->getLastTransId() == $paymentId) {
                return true;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getPaymentResult(): string
    {
        return $this->paymentResult;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $payment = $this->checkoutSession->getLastRealOrder()->getPayment();
        $this->urlData = $this->request->getParam('data');

        if (!isset($payment) ||
            !($payment->getMethod() == ConfigProvider::CODE) ||
            !$this->config->getValue(Definitions::ACTIVE) ||
            empty($this->urlData)
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function initialize(): void
    {
        try {
            $sdk = $this->config->initializeConotoxiaPay();
            $decodedReturn = $sdk->payments()->decodeReturn($this->urlData);
            $this->paymentId = $decodedReturn->getPaymentId();
            $orderId = $decodedReturn->getExternalPaymentId();
            $order = $this->orderHelper->getOrderByIncrementId($orderId);
            if (!$order) {
                $this->logger->warning('Conotoxia Pay - success page problem: Order ' . $orderId . ' not found');
                return;
            }

            if (!self::isValidOrder($order->getAllPayments(), $this->paymentId)) {
                $this->paymentResult = PaymentResults::OTHER;
            } else {
                $result = $decodedReturn->getResult();
                $this->paymentResult = $this->isValidResult($result) ? $result : PaymentResults::OTHER;
            }
        } catch (Exception $e) {
            $this->logger->warning(
                'Conotoxia Pay - success page problem: ' . $e->getMessage(),
                ['exception' => $e->getTraceAsString()]
            );
        }
    }

    /**
     * @return string
     */
    public function getConotoxiaPayLogo(): string
    {
        return $this->getViewFileUrl(Definitions::CONOTOXIA_PAY_LOGO);
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    private function isValidResult($result): bool
    {
        $validResults = [
            PaymentResults::SUCCESS,
            PaymentResults::SUCCESS_WITH_PAY_LATER,
            PaymentResults::REJECTED,
            PaymentResults::ERROR,
        ];

        return in_array($result, $validResults);
    }
}
