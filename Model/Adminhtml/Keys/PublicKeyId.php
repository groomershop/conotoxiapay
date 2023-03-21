<?php

namespace Conotoxia\Pay\Model\Adminhtml\Keys;

use Magento\Framework\App\Config\Value;

/**
 * Class PublicKeyId
 * @package Conotoxia\Pay\Model\Adminhtml
 */
class PublicKeyId extends Value
{
    private static $kid;

    public static function setKid(?string $kid)
    {
        self::$kid = $kid;
    }

    /**
     * Strip whitespace from public_key_id field value
     *
     * @return PublicKeyId
     */
    public function beforeSave(): PublicKeyId
    {
        $this->setValue(self::$kid);
        $this->setValue($this->trimStringOrEmpty($this->getValue()));

        return parent::beforeSave();
    }

    /**
     * @param string|null $value
     * @return string
     */
    public function trimStringOrEmpty(?string $value): string
    {
        return trim($value ?? '');
    }
}