<?php

declare(strict_types=1);

namespace CKPL\Pay\Notification;

/**
 * Interface NotificationManagerInterface.
 *
 * @package CKPL\Pay\Notification
 */
interface NotificationManagerInterface
{
    /**
     * Decodes received payment notification response.
     *
     * Example:
     *     $notification = $this->notification()->getNotification(\file_get_contents('php://input'));
     *
     * @param string $input
     *
     * @return NotificationInterface
     *
     * @throws \CKPL\Pay\Exception\PayloadException
     * @throws \CKPL\Pay\Exception\RefundNotificationException
     * @throws \CKPL\Pay\Exception\PaymentNotificationException
     */
    public function getNotification(string $input): NotificationInterface;
}
