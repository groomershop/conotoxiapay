<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Api\ValidationCollection\ValidationCollection;
use CKPL\Pay\Exception\Api\ValidationCollection\ValidationCollectionInterface;
use CKPL\Pay\Exception\Http\HttpBadRequestException;

/**
 * Class ValidationErrorException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class ValidationErrorException extends HttpBadRequestException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'validation-error';

    /**
     * @var ValidationCollectionInterface
     */
    protected $validationCollection;

    /**
     * @param bool $recreate
     *
     * @return ValidationCollectionInterface
     */
    public function createValidationCollection(bool $recreate = false): ValidationCollectionInterface
    {
        if ($recreate || null === $this->validationCollection) {
            $this->validationCollection = new ValidationCollection();
        }

        return $this->validationCollection;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
