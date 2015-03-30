# EnumMap
Arrays in PHP unfortunately do not support objects as keys, but enums are objects. Still every enumphile often feels the urge to map stuff to enum values. There's an object for that!

## Features
* One-to-one mapping of Enum values to mixed values
* Type checking on the porovided keys
* Iterable with Enum objects as keys
* Get/set/remove through methods and array-syntax

## But how does it work?
Imagine you have this incredibly advanced Enum:
```php
/**
 * @method static SimpleEnum FOO()
 * @method static SimpleEnum BAR()
 */
class FoobarEnum extends \GerritDrost\Lib\Enum\Enum
{
    const FOO = 'foo';
    const BAR = 'bar';
}
```
And you have this genius idea of storing strings mapped to enum values of FoobarEnum. Here's what you do:
```php
$enumMap = new GerritDrost\Lib\Enum\EnumMap(FoobarEnum::class);

// Use the map-method
$enumMap->map(FoobarEnum::FOO(), 'Some value you want to map to FoobarEnum::FOO');

// Or treat it like an array
$enumMap[FoobarEnum::BAR()] = 'Some value you want to map to FoobarEnum::BAR';
```
Woot! But now you want to access it. Here goes simple retrieval:
```php
$fooValue = $enumMap->get(FoobarEnum::FOO());
$barValue = $enumMap[FoobarEnum::BAR()];
echo $fooValue . "\n";
echo $barValue . "\n";
```
You can also iterate over all values:
```php
foreach ($enumMap as $enum => $value) {
    /* @var FoobarEnum $enum */
    echo $enum->getConstName() . ': ' . $value . "\n";
}
```
Retrieve its size
```php
echo $enumMap->size();
```
and remove some, or even all items
```php
$enumMap->remove(FoobarEnum::FOO());
unset($enumMap[FoobarEnum::BAR()]);

// Kill everything with fire! (but its already empty lol)
$enumMap->clear();
```

## Is there more?
Some people might find this sexy:
```php
$enumMap = EnumMap::create(FoobarEnum::class)
    ->map(FoobarEnum::FOO(), 'foo string')
    ->map(FoobarEnum::BAR(), 'bar string');
```
And the remove method also tells you what it deleted, pretty handy sometimes:
```php
$deletedValue = $enumMap->remove(FoobarEnum::FOO());
echo 'I deleted ' . $deletedValue . "!!1111\n";
```