<?php

namespace GerritDrost\Lib\Enum;

/**
 * @method static FoobarEnum FOO()
 * @method static FoobarEnum BAR()
 */
class FoobarEnum extends Enum
{
    const FOO = 0;
    const BAR = 1;

    private $initialized = false;
    private $foobar;

    public function isInitialized()
    {
        return $this->initialized;
    }

    public function getFoobar()
    {
        return $this->foobar;
    }

    public function __FOO() {
        $this->foobar = 'foo';
    }

    public function __BAR() {
        $this->foobar = 'bar';
    }

    protected function __init()
    {
        $this->initialized = true;
    }
}