<?php

namespace GerritDrost\Lib\Enum;

/**
 * @method static BazEnum BAZ()
 */
class BazEnum extends Enum
{
    const BAZ = 'baz';

    private $initialized = false;
    private $bazValue;

    protected function __BAZ()
    {
        $this->bazValue = $this->getConstValue();
    }

    protected function __initEnum()
    {
        $this->initialized = true;
    }

    public function isInitialized()
    {
        return $this->initialized;
    }

    public function getBazValue()
    {
        return $this->bazValue;
    }
}