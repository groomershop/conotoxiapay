<?php

namespace Conotoxia\Pay\Api;

/**
 * Class Config
 * @package Conotoxia\Pay\Model
 */
interface Definitions
{
    /**
     * @type string
     */
    const CONOTOXIA_PAY_LOGO = 'Conotoxia_Pay::images/pay_logo.svg';

    /**
     * @type string
     */
    const CONOTOXIA_PAY_ICON_ENABLED = 'payment/conotoxia_pay/required_conotoxia_pay_settings/conotoxiapay_icon';

    /**
     * @type string
     */
    const PLUGIN_VERSION = '1.12.6';

    /**
     * @type string
     */
    const PAYMENTS_HOST = 'https://partner.cinkciarz.pl';

    /**
     * @type string
     */
    const OIDC_HOST = 'https://login.cinkciarz.pl';

    /**
     * @type string
     */
    const SANDBOX_PAYMENTS_HOST = 'https://pay-api.ckpl.us';

    /**
     * @type string
     */
    const SANDBOX_OIDC_HOST = 'https://login.ckpl.io';

    /**
     * @type string
     */
    const ACTIVE = 'payment/conotoxia_pay/active';

    /**
     * @type string
     */
    const SANDBOX = 'payment/conotoxia_pay/required_conotoxia_pay_settings/sandbox';

    /**
     * @type string
     */
    const ICONS = 'payment/conotoxia_pay/icons';

    /**
     * @type string
     */
    const CLIENT_ID = 'payment/conotoxia_pay/required_conotoxia_pay_settings/client_id';

    /**
     * @type string
     */
    const CLIENT_SECRET = 'payment/conotoxia_pay/client_secret';

    /**
     * @type string
     */
    const POINT_OF_SALE = 'payment/conotoxia_pay/required_conotoxia_pay_settings/pos_id';

    /**
     * @type string
     */
    const PUBLIC_KEY = 'payment/conotoxia_pay/required_conotoxia_pay_settings/public_key';

    /**
     * @type string
     */
    const PRIVATE_KEY = 'payment/conotoxia_pay/private_key';

    /**
     * @type string
     */
    const GENERATE_KEYS_PATH = 'conotoxia_pay/configuration/generateKeysConfiguration';

    /**
     * @type string
     */
    const APPROVE_URL = 'approveUrl';

    /**
     * @type string
     */
    const PAYMENT_ID = 'paymentId';

    /**
     * @type string
     */
    const REFUND_ID = 'id';

    /**
     * @type string
     */
    const PAYMENT_DESCRIPTION = 'description';

    /**
     * @type string
     */
    const PAYMENT_EXTERNAL_ID = 'extOrderId';

    /**
     * @type string
     */
    const PAYMENT_TOTAL_AMOUNT = 'totalAmount';

    /**
     * @type string
     */
    const PAYMENT_CURRENCY_CODE = 'currencyCode';

    /**
     * @type string
     */
    const TRANSACTION_STATUS = 'Status';

    /**
     * @type string
     */
    const ADMIN_CONFIG_PRIVATE_KEY_PATH = 'groups/conotoxia_pay/groups/required_conotoxia_pay_settings/fields/private_key/value';

    /**
     * @type string
     */
    const ADMIN_CONFIG_PUBLIC_KEY_PATH = 'groups/conotoxia_pay/groups/required_conotoxia_pay_settings/fields/public_key/value';

    /**
     * @type string
     */
    const ADMIN_CONFIG_SANDBOX_PATH = 'groups/conotoxia_pay/groups/required_conotoxia_pay_settings/fields/sandbox/value';

    /**
     * @type string
     */
    const ADMIN_CONFIG_POINT_OF_SALE_ID_PATH = 'groups/conotoxia_pay/groups/required_conotoxia_pay_settings/fields/pos_id/value';

    /**
     * @type string
     */
    const ADMIN_CONFIG_CLIENT_ID_PATH = 'groups/conotoxia_pay/groups/required_conotoxia_pay_settings/fields/client_id/value';

    /**
     * @type string
     */
    const ADMIN_CONFIG_CLIENT_SECRET_PATH = 'groups/conotoxia_pay/groups/required_conotoxia_pay_settings/fields/client_secret/value';

    /**
     * @type string
     */
    const ADMIN_CONFIG_TITLE_KEY = 'legend';

    /**
     * @type string
     */
    const ADMIN_CONFIG_COMMENT_KEY = 'comment';

    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'Magento_Config::config';

    /**
     * @var string
     */
    const DOWNLOAD_PATH = 'conotoxia_pay/configuration/downloadLog';
}
