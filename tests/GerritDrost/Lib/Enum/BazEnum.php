<?php

namespace GerritDrost\Lib\Enum;

/**
 * @method static BazEnum BAZ()
 */
class BazEnum extends SimpleEnum
{
    const BAZ = 'baz';

    private $bazValue;

    protected function __BAZ()
    {
        $this->bazValue = $this->getEnumValue();
    }
}