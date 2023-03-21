<?php

namespace Conotoxia\Pay\Gateway\Request;

use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Helper\OrderHelper;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class AuthorizationRequest
 *
 * @package Conotoxia\Pay\Gateway\Request
 */
class AuthorizationRequest implements BuilderInterface
{
    /**
     * @var CheckoutSession
     */
    protected CheckoutSession $_checkoutSession;

    /**
     * @var CustomerSession
     */
    protected CustomerSession $_customerSession;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $configInterface;

    /**
     * @param ConfigInterface       $configInterface
     * @param StoreManagerInterface $storeManager
     * @param CheckoutSession       $checkoutSession
     * @param CustomerSession       $customerSession
     */
    public function __construct(
        ConfigInterface $configInterface,
        StoreManagerInterface $storeManager,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession
    ) {
        $this->configInterface = $configInterface;
        $this->storeManager = $storeManager;
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
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
        $order = $paymentData->getOrder();

        return [
            Definitions::PAYMENT_DESCRIPTION => $this->getOrderDescription($order),
            Definitions::PAYMENT_EXTERNAL_ID => $order->getOrderIncrementId(),
            Definitions::PAYMENT_TOTAL_AMOUNT => $order->getGrandTotalAmount(),
            Definitions::PAYMENT_CURRENCY_CODE => $order->getCurrencyCode(),
        ];
    }

    /**
     * @param $order
     *
     * @return string
     */
    private function getOrderDescription($order): string
    {
        return __('Order %1', $order->getOrderIncrementId());
    }
}
