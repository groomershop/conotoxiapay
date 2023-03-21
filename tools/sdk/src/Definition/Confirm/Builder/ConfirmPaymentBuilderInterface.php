<?php

namespace CKPL\Pay\Definition\Confirm\Builder;

use CKPL\Pay\Definition\Confirm\ConfirmPaymentInterface;

/**
 * Interface ConfirmPaymentBuilder.
 *
 * @package CKPL\Pay\Definition\Payment\Builder
 */
interface ConfirmPaymentBuilderInterface
{
    /**
     * @param string $blikCode
     * @return ConfirmPaymentBuilder
     */
    public function setBlikCode(string $blikCode): ConfirmPaymentBuilder;

    /**
     * @param string $type
     * @return ConfirmPaymentBuilder
     */
    public function setType(string $type): ConfirmPaymentBuilder;

    /**
     * @param string $firstName
     * @return ConfirmPaymentBuilder
     */
    public function setFirstName(string $firstName): ConfirmPaymentBuilder;

    /**
     * @param $lastName
     * @return ConfirmPaymentBuilder
     */
    public function setLastName($lastName): ConfirmPaymentBuilder;

    /**
     * @param $email
     * @return ConfirmPaymentBuilder
     */
    public function setEmail($email): ConfirmPaymentBuilder;

    /**
     * @param $token
     * @return ConfirmPaymentBuilder
     */
    public function setToken($token): ConfirmPaymentBuilder;

    /**
     * @param $singleTransactionRegulation
     * @return ConfirmPaymentBuilder
     */
    public function setSingleTransactionRegulation($singleTransactionRegulation): ConfirmPaymentBuilder;

    /**
     * @return ConfirmPaymentInterface
     */
    public function getConfirmPayment(): ConfirmPaymentInterface;
}
