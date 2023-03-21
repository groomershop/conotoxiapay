<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\GetPaymentStatusEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class RefundResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class PaymentStatusResponseModel implements ResponseModelInterface
{
    /**
     * @var string|null
     */
    protected ?string $status;

    /**
     * @var string|null
     */
    protected ?string $paymentId;

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return PaymentStatusResponseModel
     */
    public function setStatus(string $status): PaymentStatusResponseModel
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * @param string $paymentId
     * @return PaymentStatusResponseModel
     */
    public function setPaymentId(string $paymentId): PaymentStatusResponseModel
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return GetPaymentStatusEndpoint::class;
    }
}