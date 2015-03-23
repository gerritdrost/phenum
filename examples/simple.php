<?php

require __DIR__ . '/../vendor/autoload.php';

/**
 * @method static Fruit APPLE()
 * @method static Fruit BANANA()
 */
class Fruit extends GerritDrost\Lib\Enum\SimpleEnum
{
    const APPLE = 'apple';
    const BANANA = 'banana';
}

// Notice the function brackets at the end, we are secretly calling methods here
$apple = Fruit::APPLE();
$banana = Fruit::BANANA();

echo $apple->getEnumValue() . "\n";
echo $banana->getEnumName() . "\n";
echo $apple->equals($banana) ? 'equal' : 'not equal';