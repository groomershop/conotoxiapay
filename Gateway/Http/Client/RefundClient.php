<?php

namespace Conotoxia\Pay\Gateway\Http\Client;

use CKPL\Pay\Exception\Http\HttpConflictException;
use CKPL\Pay\Exception\Http\HttpNotFoundException;
use CKPL\Pay\Pay;
use Exception;
use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Logger\Logger;
use Conotoxia\Pay\Model\Configuration;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Locale\Resolver;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

/**
 * Class RefundClient
 * @package Conotoxia\Pay\Gateway\Http\Client
 */
class RefundClient implements ClientInterface
{
    /**
     * @var Configuration
     */
    private Configuration $config;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var ProductMetadataInterface
     */
    private ProductMetadataInterface $productMetadata;


    /**
     * @var Resolver
     */
    private Resolver $localeResolver;

    /**
     * @param Configuration $config
     * @param Logger $logger
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        Configuration $config,
        Logger $logger,
        ProductMetadataInterface $productMetadata,
        Resolver $localeResolver
    )
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->productMetadata = $productMetadata;
        $this->localeResolver = $localeResolver;
    }

    /**
     * @param TransferInterface $transferObject
     *
     * @return array
     *
     * @throws Exception
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        try {
            $sdk = $this->config->initializeConotoxiaPay();
            $response = $sdk->refunds()->makeRefund($this->createRefund($sdk, $transferObject));
            return [
                Definitions::REFUND_ID => $response->getId()
            ];
        } catch (HttpConflictException | HttpNotFoundException $e) {
            $message = 'Conotoxia Pay - refund creation problem: ' . $e->getMessage();
            $this->logger->warning($message, ['exception' => $e->getTraceAsString()]);

            $locale = $this->localeResolver->getLocale();
            $languageCode = strstr($locale, '_', true);
            throw new Exception($e->getTranslatedMessage($languageCode));
        } catch (Exception $e) {
            $message = 'Conotoxia Pay - refund creation problem: ' . $e->getMessage();
            $this->logger->warning($message, ['exception' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function createRefund(Pay $sdk, TransferInterface $transferObject): \CKPL\Pay\Definition\Refund\RefundInterface
    {
        $parameters = $transferObject->getBody();
        return $sdk->refunds()->createRefundBuilder()
            ->setPaymentId($parameters['TXN_ID'])
            ->setValue($this->formatTotalAmount($parameters['amount'], $parameters['currency']))
            ->setCurrency($parameters['currency'])
            ->setReason($parameters['description'])
            ->setAcceptLanguage($this->getAcceptLanguage())
            ->setIntegrationPlatform($this->getShopVersions())
            ->setExternalRefundId($parameters['externalRefundId'])
            ->getRefund();
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
