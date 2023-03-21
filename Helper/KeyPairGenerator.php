<?php

namespace Conotoxia\Pay\Helper;

use Conotoxia\Pay\Logger\Logger;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class KeyPairGenerator
 * @package Conotoxia\Pay\Helper
 */
class KeyPairGenerator
{
    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * KeysUtil constructor.
     *
     * @param Logger $logger
     * @param ManagerInterface $messageManager
     */
    public function __construct(Logger $logger, ManagerInterface $messageManager)
    {
        $this->logger = $logger;
        $this->messageManager = $messageManager;
    }

    /**
     * @param $privateKey
     *
     * @return string|null
     */
    public function getPublicKey($privateKey): ?string
    {
        if (!extension_loaded('openssl')) {
            $this->logger->warning('Conotoxia Pay - missing OpenSSL extension.');

            return null;
        }
        $keyPair = openssl_pkey_get_private($privateKey);
        if ($keyPair === false) {
            return null;
        }
        $publicKey = openssl_pkey_get_details($keyPair) ['key'];

        return trim($publicKey);
    }

    /**
     * @return array
     */
    public function handleGenerateKeys(): array
    {
        if (!extension_loaded('openssl')) {
            $message = 'Conotoxia Pay - Settings saved but there was a problem: Missing OpenSSL extension';
            $this->messageManager->addWarningMessage($message);
            $this->logger->warning($message);

            return ['public' => null, 'private' => null];
        }

        $keyPair = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        openssl_pkey_export($keyPair, $privateKey);
        $publicKey = openssl_pkey_get_details($keyPair) ['key'];

        return ['public' => $this->trimStringOrEmpty($publicKey), 'private' => $this->trimStringOrEmpty($privateKey)];
    }

    /**
     * @param string|null $value
     * @return string
     */
    public function trimStringOrEmpty(?string $value): string
    {
        return trim($value ?? '');
    }
}
