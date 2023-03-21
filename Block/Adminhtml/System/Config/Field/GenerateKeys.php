<?php

namespace Conotoxia\Pay\Block\Adminhtml\System\Config\Field;

use Conotoxia\Pay\Api\Definitions;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class GenerateKeys
 * @package Conotoxia\Pay\Block\Adminhtml\System\Config\Field
 */
class GenerateKeys extends Field
{
    /**
     * @inheritDoc
     */
    protected function _renderScopeLabel(AbstractElement $element): string
    {
        // Return empty label
        return '';
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        // Replace field markup with generation button
        $title = __('Generate new key');
        $storeId = 0;

        if ($this->getRequest()->getParam('website')) {
            $website = $this->_storeManager->getWebsite($this->getRequest()->getParam('website'));
            if ($website->getId()) {
                $store = $website->getDefaultStore();
                $storeId = $store->getStoreId();
            }
        }

        $endpoint = $this->getUrl(Definitions::GENERATE_KEYS_PATH, ['storeId' => $storeId]);

        return <<<TEXT
            <button
                type="button"
                title="{$title}"
                class="button"
                onclick="generateKeys.call(this, '{$endpoint}')">
                <span>{$title}</span>
            </button>
TEXT;
    }
}
