<?php

namespace GerritDrost\Lib;

use PHPUnit_Framework_TestCase;

class EnumTest extends PHPUnit_Framework_TestCase
{
    public function testNonExistingConst()
    {
        $this->assertNull(TestEnum::YOLO());
    }

    public function testByNameNotExists()
    {
        $bazByName = TestEnum::byName('BAZ');
        $this->assertNull($bazByName);
    }

    public function testByName()
    {
        $foo = TestEnum::FOO();
        $fooByName = TestEnum::byName('FOO');

        $this->assertNotNull($fooByName);
        $this->assertTrue($foo->equals($fooByName));
        $this->assertSame($foo, $fooByName);

        $bar = TestEnum::BAR();
        $barByName = TestEnum::byName('BAR');

        $this->assertNotNull($barByName);
        $this->assertTrue($bar->equals($barByName));
        $this->assertSame($bar, $barByName);

        $this->assertFalse($fooByName->equals($barByName));
    }

    public function testExistingConst()
    {
        $foo = TestEnum::FOO();
        $this->assertNotNull($foo);
        $this->assertEquals('FOO', $foo->getEnumName());
        $this->assertEquals(TestEnum::FOO, $foo->getEnumValue());

        $bar = TestEnum::BAR();
        $this->assertNotNull($bar);
        $this->assertEquals('BAR', $bar->getEnumName());
        $this->assertEquals(TestEnum::BAR, $bar->getEnumValue());
    }

    public function testGlobalConstructor()
    {
        $foo = TestEnum::FOO();
        $this->assertTrue($foo->isInitialized());

        $bar = TestEnum::BAR();
        $this->assertTrue($bar->isInitialized());
    }

    public function testInstanceConstructors()
    {
        $foo = TestEnum::FOO();
        $this->assertEquals('foo', $foo->getFoobar());

        $bar = TestEnum::BAR();
        $this->assertEquals('bar', $bar->getFoobar());
    }

    public function testEquals()
    {
        $foo  = TestEnum::FOO();
        $bar  = TestEnum::BAR();

        $this->assertFalse($foo->equals($bar));
        $this->assertFalse($bar->equals($foo));

        $this->assertTrue($foo->equals($foo));
        $this->assertTrue($bar->equals($bar));

        $foo2 = TestEnum::FOO();
        $this->assertTrue($foo->equals($foo2));
    }
}