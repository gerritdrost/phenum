<?php

namespace GerritDrost\Lib;

/**
 * @method static TestEnum FOO()
 * @method static TestEnum BAR()
 * @method static TestEnum BAZ()
 */
class TestEnum extends Enum
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

    protected function construct()
    {
        $this->initialized = true;
    }
}