<?php

declare(strict_types=1);

namespace Inilim\ExceptionEnum;

final class EE
{
    // static function setToE(\Throwable $e, string $class, string|int $value): void
    // {
    //     (function (string $class, string|int $value) {
    //         $this->__ee_class = $class;
    //         $this->__ee_value = $value;
    //     })->bindTo($e)->__invoke($class, $value);
    // }

    /**
     * @param class-string $class
     */
    // static function isEE(string $class, \Throwable $e): bool
    // {
    //     return (function (string $class) {
    //         return ($this->__ee_class ?? null) === $class;
    //     })->bindTo($e)->__invoke($class);
    // }

    /**
     * @return \UnitEnum|\BackedEnum|null
     */
    // static function enumFromE(\Throwable $e): ?\UnitEnum
    // {
    //     [$class, $value] = (function () {
    //         return [$this->__ee_class ?? null, $this->__ee_value ?? null];
    //     })->bindTo($e)->__invoke();

    //     if (!\is_string($class) || $value === null) {
    //         return null;
    //     }
    //     return self::enumFrom($class, $value);
    // }

    static function enumFrom(string $class, string|int $value): ?\UnitEnum
    {
        if (!\class_exists($class, true) || !($class instanceof \UnitEnum)) {
            return null;
        }
        if ($class instanceof \BackedEnum) {
            $enum = $class::tryFrom($value);
            return $enum ? $enum : null;
        }
        if (\defined($class . '::' . $value)) {
            return \constant($class . '::' . $value);
        }

        return null;
    }

    /**
     * @template T of \Throwable
     * @param T $e
     * @return T
     */
    static function rewriteLocationException(\Throwable $e, string $file, int $line): \Throwable
    {
        $rc = new \ReflectionClass($e);

        $rpf = $rc->getProperty('file');
        $rpl = $rc->getProperty('line');

        $rpf->setAccessible(true);
        $rpl->setAccessible(true);

        $rpf->setValue($e, $file);
        $rpl->setValue($e, $line);

        return $e;
    }
}
