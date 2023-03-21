<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Resolver\Resolve;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Service\Factory\DependencyFactory;

/**
 * Class Defaults.
 *
 * @package CKPL\Pay\Configuration\Resolver\Resolve
 */
final class Defaults
{
    /**
     * @type string
     */
    private const SIGN_ALGORITHM = 'SHA256';

    /**
     * @type string
     */
    private const CATEGORY = 'E_COMMERCE';

    /**
     * @return array
     */
    public static function getDefaults(): array
    {
        return [
            ConfigurationInterface::SIGN_ALGORITHM => static::getDefaultForSignAlgorithm(),
            ConfigurationInterface::DEPENDENCY_FACTORY => static::getDefaultForDependencyFactory(),
            ConfigurationInterface::CATEGORY => static::getDefaultForCategory(),
            ConfigurationInterface::CURL_OPTIONS => static::getDefaultForCurlOptions(),
        ];
    }

    /**
     * @return string
     */
    public static function getDefaultForSignAlgorithm(): string
    {
        return static::SIGN_ALGORITHM;
    }

    /**
     * @return DependencyFactory
     */
    public static function getDefaultForDependencyFactory(): DependencyFactory
    {
        return new DependencyFactory();
    }

    /**
     * @return string
     */
    public static function getDefaultForCategory(): string
    {
        return static::CATEGORY;
    }

    /**
     * @return array
     */
    public static function getDefaultForCurlOptions(): array
    {
        return [];
    }
}
