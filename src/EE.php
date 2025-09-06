<?php

declare(strict_types=1);

namespace Inilim\ExceptionEnum;

final class EE
{
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
