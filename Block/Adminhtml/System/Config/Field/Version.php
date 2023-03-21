<?php

namespace Conotoxia\Pay\Block\Adminhtml\System\Config\Field;

use Conotoxia\Pay\Api\Definitions;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Version
 * @package Conotoxia\Pay\Block\Adminhtml\System\Config\Field
 */
class Version extends Field
{
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $output = '<div style="background-color:#eee;padding:1em;border:1px solid #ddd;">';
        $output .= __('Module version') . ': ' . Definitions::PLUGIN_VERSION;
        $output .= '</div>';

        return '<div id="row_' . $element->getHtmlId() . '">' . $output . '</div>';
    }
}
