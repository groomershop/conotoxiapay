<?php

namespace Conotoxia\Pay\Api;

/**
 * Interface PaymentResults
 */
interface PaymentResults
{
    /**
     * @var string
     */
    const SUCCESS = 'SUCCESS';

    /**
     * @var string
     */
    const SUCCESS_WITH_PAY_LATER = 'SUCCESS_WITH_PAY_LATER';

    /**
     * @var string
     */
    const REJECTED = 'REJECTED';

    /**
     * @var string
     */
    const ERROR = 'ERROR';

    /**
     * @var string
     */
    const OTHER = 'OTHER';
}
