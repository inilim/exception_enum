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
    /**
     * @param string|int|float|null $message
     * @throws \Throwable
     * @return never
     */
    function throw($message = null, ?int $code = null, ?\Throwable $previous = null)
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        throw $this->_e($message, $code, $previous, $trace);
    }

    function e(?string $message = null, ?int $code = null, ?\Throwable $previous = null): \Throwable
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        return $this->_e($message, $code, $previous, $trace);
    }

    /**
     * @param (string|int|float)[] $values
     * @throws \Throwable
     * @return never
     */
    function throwFormat(array $values, ?int $code = null, ?\Throwable $previous = null)
    {
        $message = \sprintf($this->format(), $values);
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        throw $this->_e($message, $code, $previous, $trace);
    }

    /**
     * @param (string|int|float)[] $values
     */
    function eFormat(array $values, ?int $code = null, ?\Throwable $previous = null): \Throwable
    {
        $message = \sprintf($this->format(), $values);
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        return $this->_e($message, $code, $previous, $trace);
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

    /**
     * @param string|int|float|null $message
     */
    protected function _e($message, ?int $code, ?\Throwable $previous, array $trace): \Throwable
    {
        if ($message !== null) {
            $message = (string)$message;
        }
        $value = $this instanceof \BackedEnum ? $this->value : $this->name;
        $class = self::exceptionClass();
        $e = new $class(
            $message ?? $this->message(),
            $code ?? $this->code(),
            $previous
        );
        (function (string $class, string|int $value) {
            $this->__ee_class = $class;
            $this->__ee_value = $value;
        })->bindTo($e, $e)->__invoke(self::class, $value);
        EE::rewriteLocationException($e, $trace['file'], $trace['line']);
        return $e;
    }

    static function isEE(\Throwable $e): bool
    {
        return (function (string $class) {
            return ($this->__ee_class ?? null) === $class;
        })->bindTo($e, $e)->__invoke(self::class);
    }

    /**
     * @return class-string<\Throwable>
     */
    protected static function exceptionClass(): string
    {
        return Exception::class;
    }
}
