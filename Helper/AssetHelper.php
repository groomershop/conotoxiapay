<?php

namespace Conotoxia\Pay\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Conotoxia\Pay\Api\Definitions;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\View\Asset\Repository;

class AssetHelper extends AbstractHelper
{
    private Repository $assetRepository;
    private Resolver $localeResolver;

    public function __construct(Context $context, Repository $assetRepository, Resolver $localeResolver)
    {
        $this->assetRepository = $assetRepository;
        $this->localeResolver = $localeResolver;
        parent::__construct($context);
    }

    /**
     * Returns the conotoxia pay logo url
     * @return string
     */
    public function getConotoxiaPayLogo(): string
    {
        return $this->assetRepository->getUrl(Definitions::CONOTOXIA_PAY_LOGO);
    }

    /**
     * Returns an array of urls to enabled payment method icons.
     * @return array
     */
    public function getPaymentMethodIcons(): array
    {
        $paymentMethodsFromConfiguration = $this->scopeConfig->getValue(Definitions::ICONS);
        if (!isset($paymentMethodsFromConfiguration) || !is_array($paymentMethodsFromConfiguration)) {
            return [];
        }

        foreach ($paymentMethodsFromConfiguration as $paymentMethod => $isActive) {
            if (!$isActive) {
                unset($paymentMethodsFromConfiguration[$paymentMethod]);
            }
        }

        $displayOrder = array('blik', 'visa', 'mastercard', 'bancontact', 'cartes_bancaires', 'dankort', 'discover',
            'diners_club', 'jcb', 'union_pay', 'online_transfer', 'google_pay', 'apple_pay', 'pay_pal', 'skrill',
            'vipps', 'rapid_transfer', 'trustly', 'eps', 'ideal');

        $availablePaymentMethods = array_keys(array_intersect_key(array_flip($displayOrder), $paymentMethodsFromConfiguration));

        $paymentMethodIcons = [];
        $languageCode = $this->getLanguageCode();

        foreach ($availablePaymentMethods as $paymentMethod) {
            $paymentMethodByLanguage = $this->getLanguageSpecificImageName($languageCode, $paymentMethod);
            $fileId = 'Conotoxia_Pay::images/' . $paymentMethodByLanguage . '.svg';
            $iconUrl = $this->assetRepository->getUrl($fileId);
            array_push($paymentMethodIcons, $iconUrl);
        }
        return $paymentMethodIcons;
    }

    /**
     * Returns payment method icon name dedicated for language used by user.
     * @param string $paymentMethodName
     * @return string
     */
    private function getLanguageSpecificImageName(string $languageCode, string $paymentMethodName): string
    {
        if ($paymentMethodName == "online_transfer" && $languageCode == "pl") {
            return "przelew_online";
        } else {
            return $paymentMethodName;
        }
    }

    /**
     * Returns language code for current locale.
     *
     * @return string
     */
    private function getLanguageCode(): string
    {
        $locale = $this->localeResolver->getLocale();
        return strstr($locale, '_', true);
    }

    /**
     * @return bool
     */
    public function isConotoxiaPayIconEnabled(): bool
    {
        return $this->scopeConfig->getValue(Definitions::CONOTOXIA_PAY_ICON_ENABLED);
    }
}
