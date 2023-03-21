<?php

namespace Conotoxia\Pay\Controller\Adminhtml\Configuration;

use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Block\Adminhtml\System\Config\Form\DeveloperLogs;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class DownloadLog
 * Download log file via an admin link
 */
class DownloadLog extends Action
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = Definitions::ADMIN_RESOURCE;

    /**
     * @var FileFactory
     */
    private FileFactory $fileFactory;

    /**
     * DownloadLog constructor.
     *
     * @param Context     $context
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory
    ) {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $log = $this->getRequest()->getParam('name');
        $logs = DeveloperLogs::LOGS;
        if (!isset($logs[$log])) {
            throw new NotFoundException('Log "' . $log . '" does not exist');
        }

        return $this->fileFactory->create(basename($logs[$log]['path']), [
            'type' => 'filename',
            'value' => $logs[$log]['path'],
        ]);
    }
}
