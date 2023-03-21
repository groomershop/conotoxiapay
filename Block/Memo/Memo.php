<?php

namespace Conotoxia\Pay\Block\Memo;

use Magento\Framework\View\Element\Template;

/**
 * Class Memo
 * @package Conotoxia\Pay\Block\Memo
 */
class Memo extends Template
{
    /**
     * @return string
     */
    public function getPriceListUrl(): string
    {
        return __("https://conotoxia.com/payments/for-partners/pricing");
    }

    /**
     * @return string
     */
    public function getRefundFormUrl(): string
    {
        return __("https://fx.conotoxia.com/merchant/refunds/add");
    }
}
