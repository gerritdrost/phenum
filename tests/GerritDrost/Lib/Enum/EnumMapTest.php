<?php

namespace GerritDrost\Lib\Enum;

use PHPUnit_Framework_TestCase;

class EnumMapTest extends PHPUnit_Framework_TestCase {

    public function accessMethodProvider()
    {
        return [
            [
                'getter' => function(EnumMap $enumMap, Enum $enum) {
                    return $enumMap->get($enum);
                },
                'mapper' => function(EnumMap $enumMap, Enum $enum, $value) {
                    $enumMap->map($enum, $value);
                },
                'remover' => function(EnumMap $enumMap, Enum $enum) {
                    return $enumMap->remove($enum);
                },
                'checker' => function(EnumMap $enumMap, Enum $enum) {
                    return $enumMap->has($enum);
                }
            ],
            [
                'getter' => function(EnumMap $enumMap, Enum $enum) {
                    return $enumMap[$enum];
                },
                'mapper' => function(EnumMap $enumMap, Enum $enum, $value) {
                    $enumMap[$enum] = $value;
                },
                'remover' => function(EnumMap $enumMap, Enum $enum) {
                    unset($enumMap[$enum]);
                },
                'checker' => function(EnumMap $enumMap, Enum $enum) {
                    return isset($enumMap[$enum]);
                }
            ]
        ];
    }

    /**
     * @dataProvider accessMethodProvider
     */
    public function testSize($getter, $mapper, $remover, $checker)
    {
        $enumMap = EnumMap::create(TestEnum::class);
        $this->assertSame(0, $enumMap->size());

        $mapper($enumMap, TestEnum::FOO(), 'foo');
        $this->assertSame(1, $enumMap->size());

        $mapper($enumMap, TestEnum::BAR(), 'bar');
        $this->assertSame(2, $enumMap->size());

        $remover($enumMap, TestEnum::FOO());
        $this->assertSame(1, $enumMap->size());

        $remover($enumMap, TestEnum::BAR());
        $this->assertSame(0, $enumMap->size());
    }

    /**
     * @dataProvider accessMethodProvider
     */
    public function testHas($getter, $mapper, $remover, $checker)
    {
        $enumMap = EnumMap::create(TestEnum::class);

        $this->assertFalse($checker($enumMap, TestEnum::FOO()));
        $this->assertFalse($checker($enumMap, TestEnum::BAR()));

        $mapper($enumMap, TestEnum::FOO(), 'foo');
        $this->assertTrue($checker($enumMap, TestEnum::FOO()));
        $this->assertFalse($checker($enumMap, TestEnum::BAR()));

        $mapper($enumMap, TestEnum::BAR(), 'foo');
        $this->assertTrue($checker($enumMap, TestEnum::FOO()));
        $this->assertTrue($checker($enumMap, TestEnum::BAR()));

        $remover($enumMap, TestEnum::BAR());
        $this->assertTrue($checker($enumMap, TestEnum::FOO()));
        $this->assertFalse($checker($enumMap, TestEnum::BAR()));

        $remover($enumMap, TestEnum::FOO());
        $this->assertFalse($checker($enumMap, TestEnum::FOO()));
        $this->assertFalse($checker($enumMap, TestEnum::BAR()));
    }

    public function testRemove()
    {
        $fooValue = 'foo';
        $barValue = 'bar';

        $enumMap = EnumMap::create(TestEnum::class)
            ->map(TestEnum::FOO(), $fooValue)
            ->map(TestEnum::BAR(), $barValue);

        $deletedFooValue = $enumMap->remove(TestEnum::FOO());
        $this->assertSame($fooValue, $deletedFooValue);

        $deletedBarValue = $enumMap->remove(TestEnum::BAR());
        $this->assertSame($barValue, $deletedBarValue);
    }

    public function testGetEnumFQCN()
    {
        $enumMap = EnumMap::create(TestEnum::class);
        $this->assertEquals(TestEnum::class, $enumMap->getEnumFQCN());
    }

    /**
     * @dataProvider accessMethodProvider
     */
    public function testGet($getter, $mapper, $remover, $checker)
    {
        $fooValue = 'foo';
        $barValue = 'bar';

        $enumMap = EnumMap::create(TestEnum::class);
        $this->assertNull($enumMap->get(TestEnum::FOO()));
        $this->assertNull($enumMap->get(TestEnum::BAR()));

        $mapper($enumMap, TestEnum::FOO(), $fooValue);
        $this->assertSame($fooValue, $getter($enumMap, TestEnum::FOO()));
        $this->assertNull($getter($enumMap, TestEnum::BAR()));

        $mapper($enumMap, TestEnum::BAR(), $barValue);
        $this->assertSame($fooValue, $getter($enumMap, TestEnum::FOO()));
        $this->assertSame($barValue, $getter($enumMap, TestEnum::BAR()));

        $remover($enumMap, TestEnum::BAR());
        $this->assertSame($fooValue, $getter($enumMap, TestEnum::FOO()));
        $this->assertNull($getter($enumMap, TestEnum::BAR()));

        $remover($enumMap, TestEnum::FOO());
        $this->assertNull($getter($enumMap, TestEnum::FOO()));
        $this->assertNull($getter($enumMap, TestEnum::BAR()));
    }

    public function testGetNotPresentValue()
    {
        $enumMap = EnumMap::create(TestEnum::class);

        $notPresentValue = 'foobar';

        $this->assertEquals($notPresentValue, $enumMap->get(TestEnum::FOO(), $notPresentValue));
    }
}