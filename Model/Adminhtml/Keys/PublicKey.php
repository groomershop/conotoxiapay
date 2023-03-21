<?php

namespace Conotoxia\Pay\Model\Adminhtml\Keys;

use CKPL\Pay\Exception\Api\PublicKeyAlreadyExistException;
use CKPL\Pay\Exception\Http\HttpConflictException;
use CKPL\Pay\Exception\Http\HttpNotFoundException;
use Conotoxia\Pay\Api\Definitions;
use Conotoxia\Pay\Helper\KeyPairGenerator;
use Conotoxia\Pay\Logger\Logger;
use Conotoxia\Pay\Model\Configuration;
use Exception;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class PublicKey
 * @package Conotoxia\Pay\Model\Adminhtml
 */
class PublicKey extends Value
{
    /**
     * @var KeyPairGenerator
     */
    private KeyPairGenerator $keyPairGenerator;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @var Configuration
     */
    private Configuration $config;

    /**
     * @var Resolver
     */
    private Resolver $localeResolver;

    /**
     * PublicKey constructor.
     *
     * @param Configuration $config
     * @param KeyPairGenerator $keyPairGenerator
     * @param Logger $logger
     * @param ManagerInterface $messageManager
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $scopeConfig
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Configuration        $config,
        KeyPairGenerator     $keyPairGenerator,
        Logger               $logger,
        ManagerInterface     $messageManager,
        Resolver             $localeResolver,
        Context              $context,
        Registry             $registry,
        ScopeConfigInterface $scopeConfig,
        TypeListInterface    $cacheTypeList,
        AbstractResource     $resource = null,
        AbstractDb           $resourceCollection = null,
        array                $data = []
    )
    {
        parent::__construct($context, $registry, $scopeConfig, $cacheTypeList, $resource, $resourceCollection, $data);
        $this->config = $config;
        $this->keyPairGenerator = $keyPairGenerator;
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->localeResolver = $localeResolver;
    }

    /**
     * Set public key value from private key before save
     * @return PublicKey
     */
    public function beforeSave(): PublicKey
    {
        $this->sendPublicKey();

        return parent::beforeSave();
    }

    /** @noinspection PhpRedundantCatchClauseInspection */
    private function sendPublicKey(): void
    {
        $privateKey = $this->getData(Definitions::ADMIN_CONFIG_PRIVATE_KEY_PATH);
        $publicKey = $this->keyPairGenerator->getPublicKey($privateKey);

        if (!preg_match('/^\*+$/', $privateKey)) {
            $this->setPublicKey($publicKey);
        }

        if ($this->config->isAdminConfigurationCompleted($this->getData())) {
            try {
                $sdk = $this->config->initializeConotoxiaPay($this->getData());
                $sdk->merchant()->invalidateToken();
                $sdk->pickSignatureKey();
                $addedKeyResponse = $sdk->merchant()->sendPublicKey();

                if ($addedKeyResponse->getStatus() !== 'ACTIVATED') {
                    $generateKeysMessage = __('Conotoxia Pay - The generated public key is inactive. To complete the configuration, activate it in the');
                    $merchantPanel = __('Conotoxia Merchant Panel.');
                    $merchantPanelUrl = __('https://fx.conotoxia.com/merchant/configuration');
                    $this->messageManager->addComplexWarningMessage('conotoxiaPayActivateKey',
                        [
                            'message' => $generateKeysMessage . ' ' . "<a href='$merchantPanelUrl'>$merchantPanel</a>"
                        ]
                    );
                }

                PublicKeyId::setKid($addedKeyResponse->getKeyId());
                $this->messageManager->addSuccessMessage(__('Conotoxia Pay - Saved public key in Conotoxia Pay.'));

            } catch (PublicKeyAlreadyExistException $e) {
                $sdk->merchant()->setPublicKeyId($e->getKid());
                PublicKeyId::setKid($e->getKid());
                $this->logger->debug(
                    'Conotoxia Pay - saving configuration problem: ' . $e->getMessage(),
                    ['exception' => $e]
                );
            } catch (HttpConflictException | HttpNotFoundException $e) {
                $message = 'Conotoxia Pay - saving configuration problem: ' . $e->getMessage();
                $this->logger->warning($message, ['exception' => $e->getTraceAsString()]);

                $locale = $this->localeResolver->getLocale();
                $languageCode = strstr($locale, '_', true);
                $this->messageManager->addErrorMessage('Conotoxia Pay - ' . $e->getTranslatedMessage($languageCode));
            } catch (Exception $e) {
                $message = 'Conotoxia Pay - saving configuration problem: ' . $e->getMessage();
                $this->logger->warning($message, ['exception' => $e->getTraceAsString()]);
                $this->messageManager->addErrorMessage(
                    __('Conotoxia Pay - Settings saved with errors. Check log files.')
                );
            }
        } else {
            if (!empty($privateKey) && $this->getValue() === null) {
                PublicKeyId::setKid('');
                $message = 'Conotoxia Pay - Settings saved but there was a problem: Wrong private key.';
                $this->messageManager->addWarningMessage(__($message));
                $this->logger->debug($message);
            } else {
                $this->messageManager->addNoticeMessage(
                    __('Conotoxia Pay - Settings saved but some of the fields are missing.')
                );
            }
        }
    }

    /**
     * @param string|null $publicKey
     *
     * @return void
     */
    private function setPublicKey(?string $publicKey): void
    {
        $this->setValue($publicKey);

        $data = $this->getData();
        $data['groups']
        ['conotoxia_pay']
        ['groups']
        ['required_conotoxia_pay_settings']
        ['fields']
        ['public_key']
        ['value'] = $this->getValue();

        $this->setData($data);
    }
}
