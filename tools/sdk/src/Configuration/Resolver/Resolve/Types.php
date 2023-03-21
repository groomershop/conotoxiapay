<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Resolver\Resolve;

use CKPL\Pay\Configuration\ConfigurationInterface;

/**
 * Class Types.
 *
 * @package CKPL\Pay\Configuration\Resolver\Resolve
 */
final class Types
{
    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            'string' => static::getStringOptionsNames(),
            'object' => static::getObjectOptionsNames(),
            'array' => static::getArrayOptionsNames(),
        ];
    }

    /**
     * @return array
     */
    public static function getStringOptionsNames(): array
    {
        return [
            ConfigurationInterface::HOST,
            ConfigurationInterface::OIDC,
            ConfigurationInterface::CLIENT_ID,
            ConfigurationInterface::CLIENT_SECRET,
            ConfigurationInterface::SIGN_ALGORITHM,
            ConfigurationInterface::PUBLIC_KEY,
            ConfigurationInterface::PRIVATE_KEY,
            ConfigurationInterface::POINT_OF_SALE,
            ConfigurationInterface::CATEGORY,
            ConfigurationInterface::RETURN_URL,
            ConfigurationInterface::ERROR_URL,
            ConfigurationInterface::PAYMENTS_NOTIFICATION_URL,
            ConfigurationInterface::REFUNDS_NOTIFICATION_URL,
        ];
    }

    /**
     * @return array
     */
    public static function getObjectOptionsNames(): array
    {
        return [
            'CKPL\Pay\Storage\StorageInterface' => ConfigurationInterface::STORAGE,
            'CKPL\Pay\Service\Factory\DependencyFactoryInterface' => ConfigurationInterface::DEPENDENCY_FACTORY,
        ];
    }

    /**
     * @return array
     */
    public static function getArrayOptionsNames(): array
    {
        return [
            ConfigurationInterface::CURL_OPTIONS,
        ];
    }

    /**
     * @param string $optionName
     *
     * @return string|null
     */
    public static function findTypeForOption(string $optionName): ?string
    {
        $result = null;

        foreach (static::getTypes() as $type => $options) {
            foreach ($options as $key => $option) {
                if ($option === $optionName) {
                    $result = \is_string($key) ? $key : $type;

                    break;
                }
            }
        }

        return $result;
    }
}
