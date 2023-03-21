<?php

namespace Conotoxia\Pay\tools;

use CKPL\Pay\Storage\AbstractStorage;
use CKPL\Pay\Storage\StorageInterface;
use Conotoxia\Pay\Model\Configuration;

/**
 * Class ConotoxiaPayStorage
 * @package Conotoxia\Pay\tools
 */
class ConotoxiaPayStorage extends AbstractStorage
{
    const PAYMENT_CONOTOXIA_PAY = 'payment/conotoxia_pay/';

    /**
     * @var Configuration
     */
    private Configuration $config;

    /**
     * ConotoxiaPayStorage constructor.
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->items = [];
        $this->config = $config;
        $this->load();
    }

    /**
     * @return void
     */
    private function load(): void
    {
        $arrayItems = [static::TOKEN, static::PAYMENT_SERVICE_PUBLIC_KEYS];
        $stringItems = [static::PUBLIC_KEY_ID, static::PUBLIC_KEY_CHECKSUM];
        foreach ($arrayItems as $arrayItem) {
            if (!empty($this->config->getValue(self::PAYMENT_CONOTOXIA_PAY . $arrayItem))) {
                $this->items[$arrayItem] = \json_decode(
                    $this->config->getValue(self::PAYMENT_CONOTOXIA_PAY . $arrayItem),
                    true
                );
            }
        }

        foreach ($stringItems as $stringItem) {
            if (!empty($this->config->getValue(self::PAYMENT_CONOTOXIA_PAY . $stringItem))) {
                $this->items[$stringItem] = $this->config->getValue(self::PAYMENT_CONOTOXIA_PAY . $stringItem);
            }
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasItem(string $key): bool
    {
        return \array_key_exists($key, $this->items);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setItem(string $key, $value): void
    {
        if ($key === static::TOKEN || $key === static::PAYMENT_SERVICE_PUBLIC_KEYS) {
            $this->config->updateValue(
                self::PAYMENT_CONOTOXIA_PAY . $key,
                \json_encode($value, JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES)
            );
            $this->items[$key] = $value;
        } else {
            $this->config->updateValue(
                self::PAYMENT_CONOTOXIA_PAY . $key,
                $value
            );
            $this->items[$key] = (string)$value;
        }
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->config->updateValue(self::PAYMENT_CONOTOXIA_PAY . StorageInterface::TOKEN, '');
        $this->config->updateValue(self::PAYMENT_CONOTOXIA_PAY . StorageInterface::PUBLIC_KEY_CHECKSUM, '');
        $this->config->updateValue(self::PAYMENT_CONOTOXIA_PAY . StorageInterface::PAYMENT_SERVICE_PUBLIC_KEYS, '');
        $this->config->updateValue(self::PAYMENT_CONOTOXIA_PAY . StorageInterface::PUBLIC_KEY_ID, '');
    }
}
