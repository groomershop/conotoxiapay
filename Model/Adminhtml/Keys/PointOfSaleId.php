<?php

namespace Conotoxia\Pay\Model\Adminhtml\Keys;

use Magento\Framework\App\Config\Value;

/**
 * Class PointOfSaleId
 * @package Conotoxia\Pay\Model\Adminhtml
 */
class PointOfSaleId extends Value
{
    /**
     * Strip whitespace from pos_id field value
     *
     * @return PointOfSaleId
     */
    public function beforeSave(): PointOfSaleId
    {
        $this->setValue(trim($this->getValue()));

        return parent::beforeSave();
    }
}
