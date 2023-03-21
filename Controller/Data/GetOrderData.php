<?php

namespace Conotoxia\Pay\Controller\Data;

use Conotoxia\Pay\Api\Definitions;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\UrlInterface;
use Magento\Sales\Model\Order\Payment;

/**
 * Class GetOrderData
 */
class GetOrderData implements ActionInterface
{
    /**
     * @var string
     */
    const SUCCESS_FIELD = 'success';

    /**
     * @var string
     */
    const REDIRECT_URL = 'redirectUrl';

    /**
     * @var JsonFactory
     */
    private JsonFactory $resultJsonFactory;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * GetRedirectUrl constructor.
     *
     * @param JsonFactory $resultJsonFactory
     * @param Session $checkoutSession
     * @param UrlInterface $url
     */
    public function __construct(JsonFactory $resultJsonFactory, Session $checkoutSession, UrlInterface $url)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        $this->urlBuilder = $url;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var $payment Payment */
        $payment = $this->checkoutSession->getLastRealOrder()->getPayment();
        $paymentInformation = $payment->getAdditionalInformation();

        if ($this->hasApproveUrl($paymentInformation)) {
            $returnData = [
                static::SUCCESS_FIELD => true,
                static::REDIRECT_URL => $paymentInformation[Definitions::APPROVE_URL]
            ];
        } else {
            $returnData = [
                static::SUCCESS_FIELD => false,
                static::REDIRECT_URL => $this->urlBuilder->getUrl('checkout/onepage/success/')
            ];
        }

        return $this->resultJsonFactory->create()->setData($returnData);
    }

    /**
     * @param array|mixed $paymentInformation
     * @return bool
     */
    private function hasApproveUrl(array $paymentInformation): bool
    {
        return is_array($paymentInformation) && array_key_exists(Definitions::APPROVE_URL, $paymentInformation);
    }
}
