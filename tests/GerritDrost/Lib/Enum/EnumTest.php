<?php

namespace GerritDrost\Lib\Enum;

use PHPUnit_Framework_TestCase;

class EnumTest extends PHPUnit_Framework_TestCase
{
    public function testNonExistingConst()
    {
        $this->assertNull(FoobarEnum::YOLO());
    }

    public function testByNameNotExists()
    {
        $bazByName = FoobarEnum::byName('BAZ');
        $this->assertNull($bazByName);
    }

    public function testByName()
    {
        $foo = FoobarEnum::FOO();
        $fooByName = FoobarEnum::byName('FOO');

        $this->assertNotNull($fooByName);
        $this->assertTrue($foo->equals($fooByName));
        $this->assertSame($foo, $fooByName);

        $bar = FoobarEnum::BAR();
        $barByName = FoobarEnum::byName('BAR');

        $this->assertNotNull($barByName);
        $this->assertTrue($bar->equals($barByName));
        $this->assertSame($bar, $barByName);

        $this->assertFalse($fooByName->equals($barByName));
    }

    public function testExistingConst()
    {
        $foo = FoobarEnum::FOO();
        $this->assertNotNull($foo);
        $this->assertEquals('FOO', $foo->getEnumName());
        $this->assertEquals(FoobarEnum::FOO, $foo->getEnumValue());

        $bar = FoobarEnum::BAR();
        $this->assertNotNull($bar);
        $this->assertEquals('BAR', $bar->getEnumName());
        $this->assertEquals(FoobarEnum::BAR, $bar->getEnumValue());
    }

    public function testGlobalConstructor()
    {
        $foo = FoobarEnum::FOO();
        $this->assertTrue($foo->isInitialized());

        $bar = FoobarEnum::BAR();
        $this->assertTrue($bar->isInitialized());
    }

    public function testInstanceConstructors()
    {
        $foo = FoobarEnum::FOO();
        $this->assertEquals('foo', $foo->getFoobar());

        $bar = FoobarEnum::BAR();
        $this->assertEquals('bar', $bar->getFoobar());
    }

    public function testGetInstances()
    {
        $instances = FoobarEnum::getEnumInstances();

        $this->assertCount(2, $instances);
        $this->assertContains(FoobarEnum::FOO(), $instances);
        $this->assertContains(FoobarEnum::BAR(), $instances);
    }

    public function testEquals()
    {
        $foo  = FoobarEnum::FOO();
        $bar  = FoobarEnum::BAR();

        $this->assertFalse($foo->equals($bar));
        $this->assertFalse($bar->equals($foo));

        $this->assertTrue($foo->equals($foo));
        $this->assertTrue($bar->equals($bar));

        $foo2 = FoobarEnum::FOO();
        $this->assertTrue($foo->equals($foo2));
    }
}