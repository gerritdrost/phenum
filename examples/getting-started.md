# Getting Started
Below you can find a very simple enum.
```php
/**
 * @method static Fruit APPLE()
 * @method static Fruit BANANA()
 */
class Fruit extends GerritDrost\Lib\Enum\SimpleEnum
{
    const APPLE = 'apple';
    const BANANA = 'banana';
}
```
Notice the `@method` phpdocs? Those aren't actually required, but they make type-hinting in an IDE like PhpStorm work, which is quite useful.
To get an enum, you call a static method of the class of which the name is equal to the const you want the enum value for. That's a lot of words, so here are some examples:
```php
$apple = Fruit::APPLE();
$banana = Fruit::BANANA();
```
These variables now actually contain objects! Enum values behave like singletons, no matter how many of them you create/access, there is exactly one instance of every enum value available.
Imagine you want to know the name or value of the const representing the enum value afterwards. Because enum values are actually objects, this is trivial:
```php
echo $apple->getConstName() . ': ' . $apple->getConstValue();
```