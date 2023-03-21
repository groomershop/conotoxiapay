<?php

namespace Conotoxia\Pay\Controller\Adminhtml\Configuration;

use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Helper\KeyPairGenerator;
use Laminas\Http\Response;
use Magento\Backend\App\Action;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class GenerateKeysConfiguration
 * @package Conotoxia\Pay\Controller\Adminhtml\Configuration
 */
class GenerateKeysConfiguration extends Action
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = Definitions::ADMIN_RESOURCE;

    /**
     * @var KeyPairGenerator
     */
    private KeyPairGenerator $keyPairGenerator;

    /**
     * Generate constructor.
     *
     * @param Action\Context   $context
     * @param KeyPairGenerator $keyPairGenerator
     */
    public function __construct(Action\Context $context, KeyPairGenerator $keyPairGenerator)
    {
        parent::__construct($context);
        $this->keyPairGenerator = $keyPairGenerator;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $keys = $this->keyPairGenerator->handleGenerateKeys();
        $privateKey = $keys['private'];
        $publicKey = $keys['public'];

        $data = [
            'public_key' => $publicKey,
            'private_key' => $privateKey,
        ];

        return $this->buildResponse($response, $data);
    }

    /**
     * @param       $response
     * @param array $data
     *
     * @return ResultInterface
     */
    private function buildResponse($response, array $data): ResultInterface
    {
        $response->setData($data);
        $response->setHttpResponseCode(Response::STATUS_CODE_200);

        return $response;
    }
}
