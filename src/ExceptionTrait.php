<?php

declare(strict_types=1);

namespace Inilim\ExceptionEnum;

use Inilim\ExceptionEnum\EE;

/**
 * @psalm-require-extends \Exception
 * @phpstan-require-implements \Exception
 */
trait ExceptionTrait
{
    /**
     * @var class-string<\UnitEnum>
     */
    protected string $__ee_class;
    protected string|int $__ee_value;

    /**
     * @return \UnitEnum|\BackedEnum
     */
    function enum(): \UnitEnum
    {
        return EE::enumFrom($this->__ee_class, $this->__ee_value);
    }
}
