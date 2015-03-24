<?php

namespace GerritDrost\Lib\Enum;

use PHPUnit_Framework_TestCase;

class EnumMapTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EnumMap
     */
    private $enumMap;

    public function setUp()
    {
        $this->enumMap = EnumMap::create(FoobarEnum::class);
    }

    public function accessMethodProvider()
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
    public function testSize(callable $getter, callable $mapper, callable $remover, callable $checker)
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
    public function testHas(callable $getter, callable $mapper, callable $remover, callable $checker)
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

    public function constructorProvider()
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
    public function testConstruct(callable $constructor)
    {
        $enumMap = $constructor(FoobarEnum::class);

        $this->assertInstanceOf(EnumMap::class, $enumMap);
    }

    /**
     * @dataProvider constructorProvider
     * @expectedException InvalidArgumentException
     */
    public function testConstructWithInvalidEnumFQCN(callable $constructor)
    {
        $enumMap = $constructor('Yolo');
    }


    public function testRemove()
    {
        $fooValue = 'foo';
        $barValue = 'bar';

        $enumMap = $this->enumMap
            ->map(FoobarEnum::FOO(), $fooValue)
            ->map(FoobarEnum::BAR(), $barValue);

        $deletedFooValue = $enumMap->remove(FoobarEnum::FOO());
        $this->assertSame($fooValue, $deletedFooValue);

        $deletedBarValue = $enumMap->remove(FoobarEnum::BAR());
        $this->assertSame($barValue, $deletedBarValue);

        $deletedBarValue = $enumMap->remove(FoobarEnum::BAR());
        $this->assertNull($deletedBarValue);
    }

    public function testGetEnumFQCN()
    {
        $enumMap = $this->enumMap;
        $this->assertEquals(FoobarEnum::class, $enumMap->getEnumFQCN());
    }

    /**
     * @dataProvider accessMethodProvider
     */
    public function testGet(callable $getter, callable $mapper, callable $remover, callable $checker)
    {
        $fooValue = 'foo';
        $barValue = 'bar';

        $enumMap = $this->enumMap;
        $this->assertNull($enumMap->get(FoobarEnum::FOO()));
        $this->assertNull($enumMap->get(FoobarEnum::BAR()));

        $mapper($enumMap, FoobarEnum::FOO(), $fooValue);
        $this->assertSame($fooValue, $getter($enumMap, FoobarEnum::FOO()));
        $this->assertNull($getter($enumMap, FoobarEnum::BAR()));

        $mapper($enumMap, FoobarEnum::BAR(), $barValue);
        $this->assertSame($fooValue, $getter($enumMap, FoobarEnum::FOO()));
        $this->assertSame($barValue, $getter($enumMap, FoobarEnum::BAR()));

        $remover($enumMap, FoobarEnum::BAR());
        $this->assertSame($fooValue, $getter($enumMap, FoobarEnum::FOO()));
        $this->assertNull($getter($enumMap, FoobarEnum::BAR()));

        $remover($enumMap, FoobarEnum::FOO());
        $this->assertNull($getter($enumMap, FoobarEnum::FOO()));
        $this->assertNull($getter($enumMap, FoobarEnum::BAR()));
    }

    public function testClear()
    {
        $enumMap = $this->enumMap;

        $enumMap->map(FoobarEnum::FOO(), 'foo');
        $enumMap->map(FoobarEnum::BAR(), 'bar');

        $enumMap->clear();

        $this->assertNull($enumMap->get(FoobarEnum::FOO()));
        $this->assertNull($enumMap->get(FoobarEnum::BAR()));
        $this->assertEquals(0, $enumMap->size());
    }

    public function iteratorProvider() {
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
    public function testIterator(array $enumMappings)
    {
        $enumMap = $this->enumMap;

        $keys = [];
        $values = [];
        foreach ($enumMappings as $value => $key) {
            $enumMap->map($key, $value);
            $keys[] = $key;
            $values[] = $value;
        }

        foreach ($enumMap as $key => $value)
        {
            $this->assertInstanceOf(Enum::class, $key);
            $this->assertContains($key, $keys);
            $this->assertContains($value, $values);

            $keyIndex = array_search($key, $keys);
            $valueIndex = array_search($key, $keys);
            $this->assertTrue($keyIndex === $valueIndex);
        }
    }

    public function testGetNotPresentValue()
    {
        $enumMap = $this->enumMap;

        $notPresentValue = 'foobar';

        $this->assertEquals($notPresentValue, $enumMap->get(FoobarEnum::FOO(), $notPresentValue));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidHas()
    {
        $enumMap = $this->enumMap;

        $enumMap->has(BazEnum::BAZ());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidGet()
    {
        $enumMap = $this->enumMap;

        $enumMap->get(BazEnum::BAZ());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidRemove()
    {
        $enumMap = $this->enumMap;

        $enumMap->remove(BazEnum::BAZ());
    }
}