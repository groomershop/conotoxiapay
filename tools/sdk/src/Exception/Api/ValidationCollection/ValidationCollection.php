<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api\ValidationCollection;

/**
 * Class ValidationCollection.
 *
 * @package CKPL\Pay\Exception\Api\ValidationCollection
 */
class ValidationCollection implements ValidationCollectionInterface, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $messageKey
     * @param string $contextKey
     * @param string $message
     *
     * @return void
     */
    public function addError(string $messageKey, string $contextKey, string $message): void
    {
        $this->errors[] = ['message-key' => $messageKey, 'context-key' => $contextKey, 'message' => $message];
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->errors);
    }
}
