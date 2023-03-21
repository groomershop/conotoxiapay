<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Refund\Verifier;

use CKPL\Pay\Notification\Refund\RefundNotificationInterface;

/**
 * Interface VerifierInterface.
 *
 * @package CKPL\Pay\Notification\Refund\Verifier
 */
interface VerifierInterface
{
    /**
     * @return RefundNotificationInterface
     * @throws \CKPL\Pay\Exception\RefundNotificationException
     */
    public function getNotification(): RefundNotificationInterface;
}
