<?php

declare(strict_types=1);

namespace Inilim\ExceptionEnum;

use Inilim\ExceptionEnum\EE;
use Inilim\ExceptionEnum\Exception;

/**
 * @psalm-require-extends \UnitEnum|\BackedEnum
 * @phpstan-require-implements \UnitEnum|\BackedEnum
 */
trait EnumTrait
{
    static function isEE(\Throwable $e): bool
    {
        // return EE::isEE(self::class, $e);
        return (function (string $class) {
            return ($this->__ee_class ?? null) === $class;
        })->bindTo($e, $e)->__invoke(self::class);
    }

    /**
     * @throws \Throwable
     */
    function throw(?string $message = null, ?int $code = null, ?\Throwable $previous = null): void
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        throw $this->_getE($message, $code, $previous, $trace);
    }

    function getE(?string $message = null, ?int $code = null, ?\Throwable $previous = null): \Throwable
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        return $this->_getE($message, $code, $previous, $trace);
    }

    /**
     * @param (string|int|float)[] $values
     * @throws \Throwable
     */
    function throwViaFormat(array $values, ?int $code = null, ?\Throwable $previous = null): void
    {
        $message = \sprintf($this->format(), $values);
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        throw $this->_getE($message, $code, $previous, $trace);
    }

    /**
     * @param (string|int|float)[] $values
     */
    function getEViaFormat(array $values, ?int $code = null, ?\Throwable $previous = null): \Throwable
    {
        $message = \sprintf($this->format(), $values);
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        return $this->_getE($message, $code, $previous, $trace);
    }

    function code(): int
    {
        return 0;
    }

    function message(): string
    {
        return '';
    }

    function format(): string
    {
        return '';
    }

    // protected function isBacked(): bool
    // {
    //     return $this instanceof \BackedEnum;
    // }

    protected function _getE(?string $message, ?int $code, ?\Throwable $previous, array $trace): \Throwable
    {
        $value = $this instanceof \BackedEnum ? $this->value : $this->name;
        $class = self::exceptionClass();
        $e = new $class(
            $message ?? $this->message(),
            $code ?? $this->code(),
            $previous
        );
        // EE::setToE($e, $class, $value);
        (function (string $class, string|int $value) {
            $this->__ee_class = $class;
            $this->__ee_value = $value;
        })->bindTo($e, $e)->__invoke(self::class, $value);
        EE::rewriteLocationException($e, $trace['file'], $trace['line']);
        return $e;
    }

    /**
     * @return class-string<\Throwable>
     */
    protected static function exceptionClass(): string
    {
        return Exception::class;
    }
}
