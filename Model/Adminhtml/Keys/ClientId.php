<?php

namespace Conotoxia\Pay\Model\Adminhtml\Keys;

use Magento\Framework\App\Config\Value;

/**
 * Class ClientId
 * @package Conotoxia\Pay\Model\Adminhtml
 */
class ClientId extends Value
{
    /**
     * Strip whitespace from client_id field value
     *
     * @return ClientId
     */
    public function beforeSave(): ClientId
    {
        $this->setValue(trim($this->getValue()));

        return parent::beforeSave();
    }
}
