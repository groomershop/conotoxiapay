<?php

namespace Conotoxia\Pay\Block\Adminhtml\System\Config\Form;

use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Logger\Handler\Async;
use Conotoxia\Pay\Logger\Handler\Client;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\FileSystemException;

/**
 * Displays links to available custom logs
 */
class DeveloperLogs extends Field
{
    const LOGS = [
        'Logs' => ['name' => 'Logs', 'path' => Client::FILENAME],
        'AsyncLogs' => ['name' => 'AsyncLogs', 'path' => Async::FILENAME],
    ];

    /**
     * @var DirectoryList
     */
    private DirectoryList $directoryList;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        DirectoryList $directoryList,
        $data = []
    ) {
        parent::__construct($context, $data);
        $this->directoryList = $directoryList;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @throws FileSystemException
     */
    public function getLinks()
    {
        $links = $this->getLogFiles();

        if ($links) {
            $output = '';

            foreach ($links as $link) {
                $output .= '<a href="' . $link['link'] . '">' . $link['name'] . '</a><br />';
            }

            return $output;
        }

        return __('No logs are currently available.');
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/logs.phtml');
        }

        return $this;
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        return $this->_toHtml();
    }

    /**
     * @throws FileSystemException
     */
    private function getLogFiles(): array
    {
        $links = [];
        $root = $this->directoryList->getPath(DirectoryList::ROOT);
        foreach (self::LOGS as $name => $data) {
            if (file_exists($root . $data['path'])) {
                $links[] = [
                    'name' => $data['name'],
                    'link' => $this->urlBuilder->getUrl(Definitions::DOWNLOAD_PATH, [
                        'name' => $name,
                    ]),
                ];
            }
        }

        return $links;
    }
}
