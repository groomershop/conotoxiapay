<?php

namespace CKPL\Pay\Definition\Confirm;

class ConfirmPayment implements ConfirmPaymentInterface
{
    /**
     * @var string|null
     */
    protected string $blikCode;

    /**
     * @var string|null
     */
    protected string $email;

    /**
     * @var string|null
     */
    protected string $firstName;

    /**
     * @var string|null
     */
    protected string $lastName;

    /**
     * @var string|null
     */
    protected string $singleTransactionRegulation;

    /**
     * @var string|null
     */
    protected string $type;

    /**
     * @var string|null
     */
    protected string $token;

    /**
     * @return string|null
     */
    public function getBlikCode(): string
    {
        return $this->blikCode;
    }

    /**
     * @param string|null $blikCode
     * @return void
     */
    public function setBlikCode(string $blikCode)
    {
        $this->blikCode = $blikCode;
    }

    /**
     * @return string|null
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

    }

    /**
     * @return string|null
     */
    public function getSingleTransactionRegulation(): string
    {
        return $this->singleTransactionRegulation;
    }

    /**
     * @param bool $singleTransactionRegulation
     * @return void
     */
    public function setSingleTransactionRegulation(bool $singleTransactionRegulation)
    {
        $this->singleTransactionRegulation = $singleTransactionRegulation;

    }

    /**
     * @return string|null
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type)
    {
        $this->type = $type;

    }

    /**
     * @return string|null
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }
}