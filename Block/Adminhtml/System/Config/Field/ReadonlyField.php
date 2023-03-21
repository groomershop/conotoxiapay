<?php

namespace Conotoxia\Pay\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class ReadonlyField
 * @package Conotoxia\Pay\Block\Adminhtml\System\Config\Field
 */
class ReadonlyField extends Field
{
    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    public function _getElementHtml(AbstractElement $element): string
    {
        $element->setData('readonly', 1);

        return $element->getElementHtml();
    }
}
