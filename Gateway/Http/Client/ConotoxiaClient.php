<?php

namespace Conotoxia\Pay\Gateway\Http\Client;

use CKPL\Pay\Definition\Amount\AmountInterface;
use CKPL\Pay\Definition\Payment\Builder\PaymentBuilderInterface;
use CKPL\Pay\Exception\Definition\AmountException;
use CKPL\Pay\Pay;
use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Logger\Logger;
use Conotoxia\Pay\Model\Configuration;
use Exception;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

/**
 * Class ConotoxiaClient
 * @package Conotoxia\Pay\Gateway\Http\Client
 */
class ConotoxiaClient implements ClientInterface
{
    /**
     * @var ProductMetadataInterface
     */
    private ProductMetadataInterface $productMetadata;

    /**
     * @var Configuration
     */
    private Configuration $config;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param ProductMetadataInterface $productMetadata
     * @param Configuration $config
     * @param Logger $logger
     */
    public function __construct(
        ProductMetadataInterface $productMetadata,
        Configuration $config,
        Logger $logger
    )
    {
        $this->productMetadata = $productMetadata;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Places request to gateway. Returns result as ENV array
     *
     * @param TransferInterface $transferObject
     *
     * @return array
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        try {
            $sdk = $this->config->initializeConotoxiaPay();
            $response = $sdk->payments()->makePayment($this->createPayment($sdk, $transferObject));
            return [
                Definitions::APPROVE_URL => $response->getApproveUrl(),
                Definitions::PAYMENT_ID => $response->getPaymentId(),
            ];
        } catch (Exception $e) {
            $message = 'Conotoxia Pay - payment creation problem: ' . $e->getMessage();
            $this->logger->warning($message, ['exception' => $e->getTraceAsString()]);
        }
        return [];
    }

    /**
     * @param Pay $sdk
     * @param TransferInterface $transfer
     *
     * @return \CKPL\Pay\Definition\Payment\PaymentInterface
     *
     * @throws AmountException
     * @throws \CKPL\Pay\Exception\Definition\PaymentException
     */
    private function createPayment(Pay $sdk, TransferInterface $transfer): \CKPL\Pay\Definition\Payment\PaymentInterface
    {
        $body = $transfer->getBody();
        $paymentBuilder = $sdk->payments()->createPaymentBuilder();
        $paymentAmount = $this->buildPaymentAmount($paymentBuilder, $body);
        return $paymentBuilder
            ->setExternalPaymentId($body[Definitions::PAYMENT_EXTERNAL_ID])
            ->setAmount($paymentAmount)
            ->setDescription($body[Definitions::PAYMENT_DESCRIPTION])
            ->setIntegrationPlatform($this->getShopVersions())
            ->denyPayLater()
            ->setAcceptLanguage($this->getAcceptLanguage())
            ->getPayment();
    }

    /**
     * @return string|null
     */
    private function getAcceptLanguage(): ?string
    {
        return $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    /**
     * @return string
     */
    private function getShopVersions(): string
    {
        return 'MAGENTO=' . $this->productMetadata->getVersion() . ';PLUGIN=' . Definitions::PLUGIN_VERSION;
    }

    /**
     * @param PaymentBuilderInterface $paymentBuilder
     * @param array|string $body
     *
     * @return AmountInterface
     * @throws AmountException
     */
    private function buildPaymentAmount(PaymentBuilderInterface $paymentBuilder, $body): AmountInterface
    {
        $totalAmount = $body[Definitions::PAYMENT_TOTAL_AMOUNT];
        $currency = $body[Definitions::PAYMENT_CURRENCY_CODE];

        return $paymentBuilder->createAmountBuilder()
            ->setValue($this->formatTotalAmount($totalAmount, $currency))
            ->setCurrency($currency)
            ->getAmount();
    }

    /**
     * @param $amount
     * @param $currency
     *
     * @return string
     */
    private function formatTotalAmount($amount, $currency): string
    {
        if ($currency === 'HUF' || $currency === 'JPY' || $currency === 'ISK') {
            return number_format($amount, 0, '.', '');
        }
        return number_format($amount, 2, '.', '');
    }
}
