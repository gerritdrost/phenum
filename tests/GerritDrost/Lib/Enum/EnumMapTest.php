<?php

namespace GerritDrost\Lib\Enum;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EnumMapTest extends TestCase
{
    private EnumMap $enumMap;

    public function setUp(): void
    {
        $this->enumMap = EnumMap::create(FoobarEnum::class);
    }

    public static function accessMethodProvider(): array
    {
        return [
            [
                'getter' => function (EnumMap $enumMap, Enum $enum) {
                    return $enumMap->get($enum);
                },
                'mapper' => function (EnumMap $enumMap, Enum $enum, $value) {
                    $enumMap->map($enum, $value);
                },
                'remover' => function (EnumMap $enumMap, Enum $enum) {
                    return $enumMap->remove($enum);
                },
                'checker' => function (EnumMap $enumMap, Enum $enum) {
                    return $enumMap->has($enum);
                }
            ],
            [
                'getter' => function (EnumMap $enumMap, Enum $enum) {
                    return $enumMap[$enum];
                },
                'mapper' => function (EnumMap $enumMap, Enum $enum, $value) {
                    $enumMap[$enum] = $value;
                },
                'remover' => function (EnumMap $enumMap, Enum $enum) {
                    unset($enumMap[$enum]);
                },
                'checker' => function (EnumMap $enumMap, Enum $enum) {
                    return isset($enumMap[$enum]);
                }
            ]
        ];
    }

    /**
     * @dataProvider accessMethodProvider
     */
    public function testSize(callable $getter, callable $mapper, callable $remover, callable $checker): void
    {
        $enumMap = $this->enumMap;
        $this->assertSame(0, $enumMap->size());

        $mapper($enumMap, FoobarEnum::FOO(), 'foo');
        $this->assertSame(1, $enumMap->size());

        $mapper($enumMap, FoobarEnum::BAR(), 'bar');
        $this->assertSame(2, $enumMap->size());

        $remover($enumMap, FoobarEnum::FOO());
        $this->assertSame(1, $enumMap->size());

        $remover($enumMap, FoobarEnum::BAR());
        $this->assertSame(0, $enumMap->size());
    }

    /**
     * @dataProvider accessMethodProvider
     */
    public function testHas(callable $getter, callable $mapper, callable $remover, callable $checker): void
    {
        $enumMap = $this->enumMap;

        $this->assertFalse($checker($enumMap, FoobarEnum::FOO()));
        $this->assertFalse($checker($enumMap, FoobarEnum::BAR()));

        $mapper($enumMap, FoobarEnum::FOO(), 'foo');
        $this->assertTrue($checker($enumMap, FoobarEnum::FOO()));
        $this->assertFalse($checker($enumMap, FoobarEnum::BAR()));

        $mapper($enumMap, FoobarEnum::BAR(), 'foo');
        $this->assertTrue($checker($enumMap, FoobarEnum::FOO()));
        $this->assertTrue($checker($enumMap, FoobarEnum::BAR()));

        $remover($enumMap, FoobarEnum::BAR());
        $this->assertTrue($checker($enumMap, FoobarEnum::FOO()));
        $this->assertFalse($checker($enumMap, FoobarEnum::BAR()));

        $remover($enumMap, FoobarEnum::FOO());
        $this->assertFalse($checker($enumMap, FoobarEnum::FOO()));
        $this->assertFalse($checker($enumMap, FoobarEnum::BAR()));
    }

    public static function constructorProvider(): array
    {
        return [
            [
                function ($fqcn) {
                    return EnumMap::create($fqcn);
                }
            ],
            [
                function ($fqcn) {
                    return new EnumMap($fqcn);
                }
            ],
        ];
    }

    /**
     * @dataProvider constructorProvider
     */
    public function testConstruct(callable $constructor): void
    {
        $enumMap = $constructor(FoobarEnum::class);

        $this->assertInstanceOf(EnumMap::class, $enumMap);
    }

    /**
     * @dataProvider constructorProvider
     */
    public function testConstructWithInvalidEnumFQCN(callable $constructor)
    {
        $this->expectException(InvalidArgumentException::class);

        $enumMap = $constructor('Yolo');
    }


    public function testRemove(): void
    {
        $fooValue = 'foo';
        $barValue = 'bar';

        $this->enumMap
            ->map(FoobarEnum::FOO(), $fooValue)
            ->map(FoobarEnum::BAR(), $barValue);

        $deletedFooValue = $this->enumMap->remove(FoobarEnum::FOO());
        $this->assertSame($fooValue, $deletedFooValue);

        $deletedBarValue = $this->enumMap->remove(FoobarEnum::BAR());
        $this->assertSame($barValue, $deletedBarValue);

        $deletedBarValue = $this->enumMap->remove(FoobarEnum::BAR());
        $this->assertNull($deletedBarValue);
    }

    public function testGetEnumFQCN(): void
    {
        $this->assertEquals(FoobarEnum::class, $this->enumMap->getEnumFQCN());
    }

    /**
     * @dataProvider accessMethodProvider
     */
    public function testGet(callable $getter, callable $mapper, callable $remover, callable $checker): void
    {
        $fooValue = 'foo';
        $barValue = 'bar';

        $this->assertNull($this->enumMap->get(FoobarEnum::FOO()));
        $this->assertNull($this->enumMap->get(FoobarEnum::BAR()));

        $mapper($this->enumMap, FoobarEnum::FOO(), $fooValue);
        $this->assertSame($fooValue, $getter($this->enumMap, FoobarEnum::FOO()));
        $this->assertNull($getter($this->enumMap, FoobarEnum::BAR()));

        $mapper($this->enumMap, FoobarEnum::BAR(), $barValue);
        $this->assertSame($fooValue, $getter($this->enumMap, FoobarEnum::FOO()));
        $this->assertSame($barValue, $getter($this->enumMap, FoobarEnum::BAR()));

        $remover($this->enumMap, FoobarEnum::BAR());
        $this->assertSame($fooValue, $getter($this->enumMap, FoobarEnum::FOO()));
        $this->assertNull($getter($this->enumMap, FoobarEnum::BAR()));

        $remover($this->enumMap, FoobarEnum::FOO());
        $this->assertNull($getter($this->enumMap, FoobarEnum::FOO()));
        $this->assertNull($getter($this->enumMap, FoobarEnum::BAR()));
    }

    public function testClear(): void
    {
        $this->enumMap->map(FoobarEnum::FOO(), 'foo');
        $this->enumMap->map(FoobarEnum::BAR(), 'bar');

        $this->enumMap->clear();

        $this->assertNull($this->enumMap->get(FoobarEnum::FOO()));
        $this->assertNull($this->enumMap->get(FoobarEnum::BAR()));
        $this->assertEquals(0, $this->enumMap->size());
    }

    public static function iteratorProvider(): array
    {
        return [
            [[
                'foo' => FoobarEnum::FOO(),
                'bar' => FoobarEnum::BAR()
            ]],
            [[
                'foo' => FoobarEnum::FOO()
            ]],
            [[
                'bar' => FoobarEnum::BAR()
            ]]
        ];
    }

    /**
     * @dataProvider iteratorProvider
     *
     * @param array $enumMappings
     */
    public function testIterator(array $enumMappings): void
    {
        $keys = [];
        $values = [];
        foreach ($enumMappings as $value => $key) {
            $this->enumMap->map($key, $value);
            $keys[] = $key;
            $values[] = $value;
        }

        foreach ($this->enumMap as $key => $value)
        {
            $this->assertInstanceOf(Enum::class, $key);
            $this->assertContains($key, $keys);
            $this->assertContains($value, $values);

            $keyIndex = array_search($key, $keys);
            $valueIndex = array_search($key, $keys);
            $this->assertTrue($keyIndex === $valueIndex);
        }
    }

    public function testGetNotPresentValue(): void
    {
        $notPresentValue = 'foobar';

        $this->assertEquals($notPresentValue, $this->enumMap->get(FoobarEnum::FOO(), $notPresentValue));
    }

    public function testInvalidHas(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->enumMap->has(BazEnum::BAZ());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidGet()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->enumMap->get(BazEnum::BAZ());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidRemove()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->enumMap->remove(BazEnum::BAZ());
    }
}