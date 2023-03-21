<?php

namespace CKPL\Pay\Definition\Confirm;

interface ConfirmPaymentInterface
{

    /**
     * @return string
     */
    public function getBlikCode(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @return string
     */
    public function getSingleTransactionRegulation(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getToken(): string;
}