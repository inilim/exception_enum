<?php

require_once __DIR__ . '/vendor/autoload.php';

use Inilim\Dump\Dump;
use Inilim\ExceptionEnum\EnumTrait;

Dump::init();


enum Test: int
{
    use EnumTrait;

    case item = 1;
    case item_2 = 2;

    function message(): string
    {
        return match ($this) {
            self::item => 'КУКУ',
            default => '',
        };
    }
}

$e = Test::item_2->getE();

de($e);
