<?php

namespace Conotoxia\Pay\Model;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Configuration\Factory\ConfigurationFactory;
use CKPL\Pay\Exception\ConfigurationException;
use CKPL\Pay\Pay;
use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\tools\ConotoxiaPayStorage;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

/**
 * Class Configuration
 * @package Conotoxia\Pay\Model
 */
class Configuration implements Definitions
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var WriterInterface
     */
    private Writerinterface $configWriter;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface      $configWriter
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
    }

    /**
     * @param      $path
     * @param      $value
     * @param null $scopeCode
     */
    public function updateValue($path, $value, $scopeCode = 0)
    {
        $this->configWriter->save(
            $path,
            $value,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $scopeCode
        );
    }

    /**
     * @param array $data
     *
     * @return Pay
     * @throws ConfigurationException
     */
    public function initializeConotoxiaPay(array $data = []): Pay
    {
        if (!empty($data)) {
            return new Pay($this->makeConfigurationForAdmin($data));
        }

        return new Pay($this->makeConfiguration());
    }

    /**
     * @param      $path
     * @param null $scopeCode
     *
     * @return mixed
     */
    public function getValue($path, $scopeCode = null)
    {
        return $this->scopeConfig->getValue($path, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function isAdminConfigurationCompleted(array $data): bool
    {
        $required = [
            Definitions::ADMIN_CONFIG_CLIENT_ID_PATH,
            Definitions::ADMIN_CONFIG_CLIENT_SECRET_PATH,
            Definitions::ADMIN_CONFIG_POINT_OF_SALE_ID_PATH,
            Definitions::ADMIN_CONFIG_PUBLIC_KEY_PATH,
            Definitions::ADMIN_CONFIG_PRIVATE_KEY_PATH,
        ];
        $result = true;

        foreach ($required as $option) {
            if (empty($this->getDataByPath($data, $option))) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    /**
     * @return ConfigurationInterface
     * @throws ConfigurationException
     */
    private function makeConfiguration(): ConfigurationInterface
    {
        return ConfigurationFactory::fromArray(
            [
                ConfigurationInterface::HOST => $this->getPaymentHost(),
                ConfigurationInterface::OIDC => $this->getOidcHost(),
                ConfigurationInterface::CLIENT_ID => $this->getValue(Definitions::CLIENT_ID),
                ConfigurationInterface::CLIENT_SECRET => $this->getValue(Definitions::CLIENT_SECRET),
                ConfigurationInterface::POINT_OF_SALE => $this->getValue(Definitions::POINT_OF_SALE),
                ConfigurationInterface::PRIVATE_KEY => $this->getValue(Definitions::PRIVATE_KEY),
                ConfigurationInterface::PUBLIC_KEY => $this->getValue(Definitions::PUBLIC_KEY),
                ConfigurationInterface::STORAGE => new ConotoxiaPayStorage($this),
            ]
        );
    }

    /**
     * @param array $data
     *
     * @return ConfigurationInterface
     * @throws ConfigurationException
     */
    private function makeConfigurationForAdmin(array $data): ConfigurationInterface
    {
        return ConfigurationFactory::fromArray(
            [
                ConfigurationInterface::HOST => $this->getPaymentHostAdmin(
                    $this->getDataByPath($data, Definitions::ADMIN_CONFIG_SANDBOX_PATH)
                ),
                ConfigurationInterface::OIDC => $this->getOidcHostAdmin(
                    $this->getDataByPath($data, Definitions::ADMIN_CONFIG_SANDBOX_PATH)
                ),
                ConfigurationInterface::CLIENT_ID => $this->getDataByPath(
                    $data,
                    Definitions::ADMIN_CONFIG_CLIENT_ID_PATH
                ),
                ConfigurationInterface::CLIENT_SECRET => $this->getAsteriskValue(
                    $data,
                    Definitions::ADMIN_CONFIG_CLIENT_SECRET_PATH,
                    Definitions::CLIENT_SECRET
                ),
                ConfigurationInterface::POINT_OF_SALE => $this->getDataByPath(
                    $data,
                    Definitions::ADMIN_CONFIG_POINT_OF_SALE_ID_PATH
                ),
                ConfigurationInterface::PRIVATE_KEY => $this->getAsteriskValue(
                    $data,
                    Definitions::ADMIN_CONFIG_PRIVATE_KEY_PATH,
                    Definitions::PRIVATE_KEY
                ),
                ConfigurationInterface::PUBLIC_KEY => $this->getDataByPath(
                    $data,
                    Definitions::ADMIN_CONFIG_PUBLIC_KEY_PATH
                ),
                ConfigurationInterface::STORAGE => new ConotoxiaPayStorage($this),
            ]
        );
    }

    private function getAsteriskValue(array $data, string $adminPath, string $dbPath)
    {
        $value = $this->getDataByPath($data, $adminPath);
        if (empty($value) || preg_match('/^\*+$/', $value)) {
            $value = $this->getValue($dbPath);
        }

        return $value;
    }

    /**
     * @return string
     */
    private function getPaymentHost(): string
    {
        if ($this->getValue(Definitions::SANDBOX)) {
            return Definitions::SANDBOX_PAYMENTS_HOST;
        }

        return Definitions::PAYMENTS_HOST;
    }

    /**
     * @return string
     */
    private function getOidcHost(): string
    {
        if ($this->getValue(Definitions::SANDBOX)) {
            return Definitions::SANDBOX_OIDC_HOST;
        }

        return Definitions::OIDC_HOST;
    }

    /**
     * @param $sandboxEnabled
     *
     * @return string
     */
    private function getPaymentHostAdmin($sandboxEnabled): string
    {
        if ($sandboxEnabled) {
            return Definitions::SANDBOX_PAYMENTS_HOST;
        }

        return Definitions::PAYMENTS_HOST;
    }

    /**
     * @param $sandboxEnabled
     *
     * @return string
     */
    private function getOidcHostAdmin($sandboxEnabled): string
    {
        if ($sandboxEnabled) {
            return Definitions::SANDBOX_OIDC_HOST;
        }

        return Definitions::OIDC_HOST;
    }

    /**
     * @param array  $data
     * @param string $path
     *
     * @return mixed
     */
    private function getDataByPath(array $data, string $path)
    {
        $keys = explode('/', $path);

        foreach ($keys as $key) {
            if ((array) $data === $data && isset($data[$key])) {
                $data = $data[$key];
            } else {
                return null;
            }
        }

        return $data;
    }
}