<?php

namespace Conotoxia\Pay\Block\Adminhtml\System\Config\Field;

use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\Obscure;
use Magento\Framework\Escaper;

/**
 * Class ObscureTextarea
 * @package Conotoxia\Pay\Block\Adminhtml\System\Config\Field
 */
class ObscureTextarea extends Obscure
{
    /**
     * Default number of rows
     */
    const DEFAULT_ROWS = 2;

    /**
     * Default number of columns
     */
    const DEFAULT_COLS = 15;

    /**
     * @param Factory           $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper           $escaper
     * @param array             $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('textarea');
        $this->setExtType('textarea');
        if (!$this->getRows()) {
            $this->setRows(self::DEFAULT_ROWS);
        }
        if (!$this->getCols()) {
            $this->setCols(self::DEFAULT_COLS);
        }
    }

    /**
     * Return the HTML attributes
     *
     * @return string[]
     */
    public function getHtmlAttributes(): array
    {
        return [
            'title',
            'class',
            'style',
            'onclick',
            'onchange',
            'rows',
            'cols',
            'readonly',
            'maxlength',
            'disabled',
            'onkeyup',
            'tabindex',
            'data-form-part',
            'data-role',
            'data-action',
        ];
    }

    /**
     * Return the element as HTML
     *
     * @return string
     */
    public function getElementHtml(): string
    {
        $this->addClass('textarea admin__control-textarea');
        $html = '<textarea id="' . $this->getHtmlId() . '" name="' . $this->getName() . '" '
            . $this->serialize($this->getHtmlAttributes()) . $this->_getUiId() . ' >';
        $html .= $this->getEscapedValue();
        $html .= "</textarea>";
        $html .= $this->getAfterElementHtml();

        return $html;
    }
}
