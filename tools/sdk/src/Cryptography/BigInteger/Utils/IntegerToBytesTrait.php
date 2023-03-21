<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Utils;

/**
 * Trait IntegerToBytesTrait.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Utils
 */
trait IntegerToBytesTrait
{
    /**
     * @param int $value
     *
     * @return string
     */
    protected function integerToBytes(int $value): string
    {
        return \ltrim(\pack('N', $value), \chr(0));
    }
}
