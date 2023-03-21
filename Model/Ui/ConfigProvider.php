<?php

namespace Conotoxia\Pay\Model\Ui;

use Conotoxia\Pay\Helper\AssetHelper;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 * @package Conotoxia\Pay\Model\Ui
 */
final class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @type string
     */
    public const CODE = 'conotoxia_pay';

    /**
     * @var AssetHelper
     */
    private AssetHelper $assetHelper;

    /**
     * ConfigProvider constructor.
     * @param AssetHelper $assetHelper
     */
    public function __construct(AssetHelper $assetHelper)
    {
        $this->assetHelper = $assetHelper;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'payment' => [
                self::CODE => [
                    'conotoxiaPayLogo' => $this->assetHelper->getConotoxiaPayLogo(),
                    'paymentMethodIcons' => $this->assetHelper->getPaymentMethodIcons(),
                    'isConotoxiaPayIconEnabled' => $this->assetHelper->isConotoxiaPayIconEnabled()
                ]
            ]
        ];
    }
}
