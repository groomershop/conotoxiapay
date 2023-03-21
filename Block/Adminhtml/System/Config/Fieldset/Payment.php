<?php

namespace Conotoxia\Pay\Block\Adminhtml\System\Config\Fieldset;

use Conotoxia\Pay\Api\Definitions;
use Magento\Backend\Block\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Config\Model\Config;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\Js;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

/**
 * Class Payment
 * @package Conotoxia\Pay\Block\Adminhtml\System\Config\Fieldset
 */
class Payment extends Fieldset
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var SecureHtmlRenderer
     */
    private SecureHtmlRenderer $secureRenderer;

    /**
     * @param Context            $context
     * @param Session            $authSession
     * @param Js                 $jsHelper
     * @param Config             $config
     * @param array              $data
     * @param SecureHtmlRenderer $secureRenderer
     */
    public function __construct(
        Context $context,
        Session $authSession,
        Js $jsHelper,
        Config $config,
        SecureHtmlRenderer $secureRenderer,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $authSession,
            $jsHelper,
            $data,
            $secureRenderer
        );
        $this->config = $config;
        $this->secureRenderer = $secureRenderer;
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     * @noinspection PhpMissingParamTypeInspection
     */
    protected function _getFrontendClass($element): string
    {
        return parent::_getFrontendClass($element) . ' with-button';
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     * @noinspection PhpMissingParamTypeInspection
     */
    protected function _getHeaderTitleHtml($element): string
    {
        $title = $element->getData(Definitions::ADMIN_CONFIG_TITLE_KEY);
        $comment = $element->getData(Definitions::ADMIN_CONFIG_COMMENT_KEY);
        $htmlId = $element->getHtmlId();
        $configureText = __('Configure');
        $closeText = __('Close');
        $buttonAction = $this->secureRenderer->renderEventListenerAsTag(
            'onclick',
            "toggleConotoxiaSolution.call(this, '" . $htmlId . "', '" . $this->getUrl('adminhtml/*/state') .
            "');event.preventDefault();",
            'button#' . $htmlId . '-head'
        );

        return <<<HTML
        <div class="config-heading">
            <div class="button-container">
                <button type="button" 
                        class="button action-configure"
                        id="$htmlId-head">
                    <span class="state-closed">$configureText</span>
                    <span class="state-opened">$closeText</span>
                </button>
                $buttonAction
            </div>
            <div class="heading">
                <strong>$title</strong>
                <span class="heading-intro">$comment</span>
                <div class="config-alt"></div>
            </div>
        </div>
HTML;
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     * @noinspection PhpMissingParamTypeInspection
     */
    protected function _getHeaderCommentHtml($element): string
    {
        return '';
    }

    /**
     * @param AbstractElement $element
     *
     * @return false
     * @noinspection PhpMissingParamTypeInspection
     */
    protected function _isCollapseState($element): bool
    {
        return false;
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     * @noinspection PhpMissingParamTypeInspection
     */
    protected function _getExtraJs($element): string
    {
        $text = <<<SCRIPT
            require(['jquery', 'prototype'], function(jQuery){
                window.toggleConotoxiaSolution = function (id, url) {
                    var doScroll = false;
                    Fieldset.toggleCollapse(id, url);
                    if ($(this).hasClassName("open")) {
                        $(".with-button button.button").each(function(anotherButton) {
                            if (anotherButton != this && $(anotherButton).hasClassName("open")) {
                                $(anotherButton).click();
                                doScroll = true;
                            }
                        }.bind(this));
                    }
                    if (doScroll) {
                        var pos = Element.cumulativeOffset($(this));
                        window.scrollTo(pos[0], pos[1] - 45);
                    }
                }
            });
SCRIPT;

        return $this->_jsHelper->getScript($text);
    }
}
