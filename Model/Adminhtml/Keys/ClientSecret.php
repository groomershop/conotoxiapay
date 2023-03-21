<?php

namespace Conotoxia\Pay\Model\Adminhtml\Keys;

use Magento\Config\Model\Config\Backend\Encrypted;

/**
 * Class ClientSecret
 * @package Conotoxia\Pay\Model\Adminhtml
 */
class ClientSecret extends Encrypted
{
    /**
     * Encrypt client secret before save
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->setValue(trim($this->getValue()));

        parent::beforeSave();
    }
}
