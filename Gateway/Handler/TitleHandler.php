<?php

namespace Conotoxia\Pay\Gateway\Handler;

use Magento\Payment\Gateway\Config\ValueHandlerInterface;

/**
 * Class TitleHandler
 * @package Conotoxia\Pay\Gateway\Handler
 */
class TitleHandler implements ValueHandlerInterface
{
    /**
     * @type string
     */
    const DEFAULT_TITLE = 'Conotoxia Pay';

    /**
     * @param array    $subject
     * @param int|null $storeId
     *
     * @return string
     */
    public function handle(array $subject, $storeId = null): string
    {
        return __(self::DEFAULT_TITLE);
    }
}