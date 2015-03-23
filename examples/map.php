<?php

require __DIR__ . '/../vendor/autoload.php';

/**
 * @method static CarBrand VOLVO()
 * @method static CarBrand AUDI()
 * @method static CarBrand DACIA()
 */
class CarBrand extends GerritDrost\Lib\Enum\SimpleEnum
{
    const VOLVO  = 'Volvo';
    const AUDI   = 'Audi';
    const DACIA  = 'Dacia';

    /**
     * @return string
     */
    public function getBrandName()
    {
        return $this->getEnumValue();
    }
}

// Notice the function brackets at the end, we are secretly calling methods here
$volvo = CarBrand::VOLVO();
$audi  = CarBrand::AUDI();
$dacia = CarBrand::DACIA();

$enumMap = new GerritDrost\Lib\Enum\EnumMap(CarBrand::class);

// Use it using the map method
$enumMap->map(CarBrand::VOLVO(), ['C30', 'V40', 'S60', 'V60', 'XC90']);
print_r($enumMap->get($volvo));

// Or using ArrayAccess
$enumMap[CarBrand::AUDI()] = ['A3', 'A4', 'RS6', 'TT', 'R8'];
print_r($enumMap[$audi]);


echo $volvo->getBrandName() . ': ' . (
    $enumMap->has(CarBrand::VOLVO())
        ? 'Yes'
        : 'No'
) . "\n";

echo $dacia->getBrandName() . ': ' . (
    $enumMap->has(CarBrand::DACIA())
        ? 'Yes'
        : 'No'
) . "\n";

foreach ($enumMap as $enum => $value) {
    print_r(array('enum' => $enum, 'value' => $value));
}

unset($enumMap[$audi]);

foreach ($enumMap as $enum => $value) {
    print_r(array('enum' => $enum, 'value' => $value));
}