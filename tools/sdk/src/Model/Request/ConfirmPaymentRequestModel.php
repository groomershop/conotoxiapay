<?php

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\ConfirmPaymentEndpoint;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class ConfirmPaymentRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class ConfirmPaymentRequestModel implements RequestModelInterface
{
    /**
     * @var string
     */
    private string $blikCode;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var bool
     */
    private bool $singleTransactionRegulation;

    /**
     * @param string $blikCode
     * @return RequestModelInterface
     */
    public function setBlikCode(string $blikCode): RequestModelInterface
    {
        $this->blikCode = $blikCode;

        return $this;
    }

    /**
     * @param string $type
     * @return RequestModelInterface
     */
    public function setPaymentMethodType(string $type): RequestModelInterface
    {
        $this->type = $type;

        return $this;
    }


    /**
     * @param string $email
     * @return RequestModelInterface
     */
    public function setEmail(string $email): RequestModelInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $firstName
     * @return RequestModelInterface
     */
    public function setFirstName(string $firstName): RequestModelInterface
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param string $lastName
     * @return RequestModelInterface
     */
    public function setLastName(string $lastName): RequestModelInterface
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @param bool $singleTransactionRegulation
     * @return RequestModelInterface
     */
    public function setSingleTransactionRegulation(bool $singleTransactionRegulation): RequestModelInterface
    {
        $this->singleTransactionRegulation = $singleTransactionRegulation;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return ConfirmPaymentEndpoint::class;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return static::JSON_OBJECT;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        $additionalData = (object)[
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'singleTransactionRegulation' => $this->singleTransactionRegulation,
        ];

        return [
            'blikCode' => $this->blikCode,
            'type' => $this->type,
            'additionalData' => $additionalData
        ];
    }
}
