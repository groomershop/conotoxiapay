<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Utils;

/**
 * Trait BytesToIntegerTrait.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Utils
 */
trait BytesToIntegerTrait
{
    /**
     * @param string $values
     *
     * @return int
     */
    protected function bytesToInteger(string $values): int
    {
        $value = \unpack('Nint', \str_pad($values, 4, \chr(0), STR_PAD_LEFT));

        return $value['int'];
    }
}
