<?php
declare(strict_types=1);

namespace CKPL\Pay\Definition\Confirm\Builder;

use CKPL\Pay\Definition\Confirm\ConfirmPayment;
use CKPL\Pay\Definition\Confirm\ConfirmPaymentInterface;
use CKPL\Pay\Exception\Definition\ConfirmPaymentException;

class ConfirmPaymentBuilder implements ConfirmPaymentBuilderInterface
{

    /**
     * @var ConfirmPayment
     */
    protected ConfirmPayment $confirmPayment;

    /**
     * ConfirmPaymentBuilder constructor.
     */
    public function __construct()
    {
        $this->initializeConfirmPayment();
    }

    /**
     * @return void
     */
    protected function initializeConfirmPayment(): void
    {
        $this->confirmPayment = new ConfirmPayment();
    }

    public function setBlikCode(string $blikCode): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setBlikCode($blikCode);

        return $this;
    }

    public function setType(string $type): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setType($type);

        return $this;
    }

    public function setFirstName(string $firstName): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setFirstName($firstName);

        return $this;
    }

    public function setLastName($lastName): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setLastName($lastName);

        return $this;
    }

    public function setSingleTransactionRegulation($singleTransactionRegulation): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setSingleTransactionRegulation($singleTransactionRegulation);

        return $this;
    }

    public function setToken($token): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setToken($token);

        return $this;
    }

    public function setEmail($email): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setEmail($email);

        return $this;
    }

    /**
     * @throws ConfirmPaymentException
     */
    public function getConfirmPayment(): ConfirmPaymentInterface
    {
        if (null === $this->confirmPayment->getToken()) {
            throw new ConfirmPaymentException('Missing token in confirm payment request.');
        }

        if (null === $this->confirmPayment->getLastName()) {
            throw new ConfirmPaymentException('Missing last name in confirm payment request.');
        }

        if (null === $this->confirmPayment->getType()) {
            throw new ConfirmPaymentException('Missing type in confirm payment request.');
        }

        if (null === $this->confirmPayment->getFirstName()) {
            throw new ConfirmPaymentException('Missing first name in confirm payment request.');
        }

        if (null === $this->confirmPayment->getBlikCode()) {
            throw new ConfirmPaymentException('Missing blik code in confirm payment request.');
        }

        if (null === $this->confirmPayment->getSingleTransactionRegulation()) {
            throw new ConfirmPaymentException('Missing regulation flag in confirm payment request.');
        }

        if (null === $this->confirmPayment->getEmail()) {
            throw new ConfirmPaymentException('Missing email in confirm payment request.');
        }

        return $this->confirmPayment;
    }
}