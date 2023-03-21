<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification\Payment\Verifier;

use CKPL\Pay\Notification\Payment\PaymentNotificationInterface;

/**
 * Interface VerifierInterface.
 *
 * @package CKPL\Pay\Notification\Payment\Verifier
 */
interface VerifierInterface
{
    /**
     * @return PaymentNotificationInterface
     * @throws \CKPL\Pay\Exception\PaymentNotificationException
     */
    public function getNotification(): PaymentNotificationInterface;
}
