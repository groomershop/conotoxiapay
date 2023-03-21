<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class PaymentMoneyBelowLimitException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class PaymentMoneyBelowLimitException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'payment-money-below-limit';

    protected $messages = [
        'pl' => 'Podana kwota płatności znajduje się poniżej zdefiniowanej wartości dla danej waluty.',
        'en' => 'The indicated payment amount is below the defined value for a given currency.'
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
