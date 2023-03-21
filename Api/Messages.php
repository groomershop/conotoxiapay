<?php

namespace Conotoxia\Pay\Api;

/**
 * Interface Messages
 * @package Conotoxia\Pay\Api
 */
interface Messages
{
    /**
     * @type string
     */
    const PAYMENT_CREATED = 'Conotoxia Pay: Payment has been created. Transaction ID: "%1"';

    /**
     * @type string
     */
    const PAYMENT_CANCELLED = 'Conotoxia Pay: Payment has been cancelled. Transaction ID: "%1"';

    /**
     * @type string
     */
    const PAYMENT_COMPLETED = 'Conotoxia Pay: Payment has been completed. Transaction ID: "%1"';

    /**
     * @type string
     */
    const REFUND_CREATED = 'Conotoxia Pay: Refund has been created. Transaction ID: "%1"';

    /**
     * @type string
     */
    const REFUND_PROCESSING = 'Conotoxia Pay: Refund is being processed. Transaction ID: "%1"';

    /**
     * @type string
     */
    const REFUND_PENDING = 'Conotoxia Pay: Refund has been suspended. Transaction ID: "%1"';

    /**
     * @type string
     */
    const REFUND_COMPLETED = 'Conotoxia Pay: Refund has been completed. Transaction ID: "%1"';

    /**
     * @type string
     */
    const REFUND_CANCELLED = 'Conotoxia Pay: Refund has been cancelled. Transaction ID: "%1"';
}
