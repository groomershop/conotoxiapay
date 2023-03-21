<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

use CKPL\Pay\Exception\Api\TranslationInterface;

/**
 * Class HttpNotFoundException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class HttpNotFoundException extends HttpException implements TranslationInterface
{
    /**
     * @type int
     */
    const STATUS_CODE = 404;

    /**
     * @type array
     */
    protected $messages = [];

    /**
     * Gets translated message.
     *
     * @param $languageCode string Language iso code.
     * @return string|null
     */
    public function getTranslatedMessage($languageCode): ?string
    {
        return $this->messages[$languageCode] ?? null;
    }
}
