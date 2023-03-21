<?php

declare(strict_types=1);

namespace CKPL\Pay;

/**
 * Class Autoload.
 *
 * @package CKPL\Pay
 */
final class Autoload
{
    /**
     * @var array|string[]
     */
    private static $loaded = [];

    /**
     * @var string
     */
    private $class;

    /**
     * @param string $className
     *
     * @return void
     */
    public static function resolve(string $className): void
    {
        $class = new static($className);

        if ($class->isInternal()) {
            $parts = $class->getParts();
            $directoryPath = static::findDirectory($parts);
            $filename = static::getFilename($parts);

            if (\file_exists($directoryPath.\DIRECTORY_SEPARATOR.$filename)) {
                require_once $directoryPath.\DIRECTORY_SEPARATOR.$filename;

                self::$loaded[] = $className;
            }
        }
    }

    /**
     * @return array
     */
    public static function getLoaded(): array
    {
        return self::$loaded;
    }

    /**
     * Autoload constructor.
     *
     * @param string $class
     */
    private function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @return bool
     */
    public function isInternal(): bool
    {
        return 'CKPL\Pay' === \substr($this->class, 0, 8);
    }

    /**
     * @return array
     */
    public function getParts(): array
    {
        return \explode('\\', \substr($this->class, 9));
    }

    /**
     * @param array $parts
     *
     * @return string
     */
    private static function findDirectory(array $parts): string
    {
        $result = [__DIR__, '..', 'src'];

        foreach ($parts as $part) {
            $result[] = $part;
        }

        \array_pop($result);

        return \join(\DIRECTORY_SEPARATOR, $result);
    }

    /**
     * @param array $parts
     *
     * @return string
     */
    private static function getFilename(array $parts): string
    {
        $lastPart = \array_pop($parts);

        return $lastPart.'.php';
    }
}
