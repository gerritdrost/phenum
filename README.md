# phenum
Enums for PHP, nuff said.

## Description
A lot of people love enums. Unfortunately, PHP does not support them out-of-the-box, it requires `SplEnum` to be installed. I stumbled across this problems as well and decided to attempt to solve it: say hello to phenum.

## Why phenum?
Because it's easy to use and has some cool features. Enum values are actually are singleton objects, they have their own instance and can have their own variables! Also the library provides utility classes like the EnumMap which can be used to map values to Enums, something not possible with regular PHP arrays.

## Setup
Include [`gerritdrost/phenum`](https://packagist.org/packages/gerritdrost/phenum) using composer.

## Examples

### Simple enum
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

// Notice the function brackets at the end, we are secretly calling methods here
$apple = Fruit::APPLE();
$banana = Fruit::BANANA();

// getEnumValue() returns the const value
echo $apple->getEnumValue() . "\n";
// getEnumName() returns the const name
echo $banana->getEnumName() . "\n";
// Equals is only true when the enums are of the same class and represent the same const
echo $apple->equals($banana) ? 'equal' : 'not equal';

--- Output
apple
BANANA
not equal
```

### With global constructor
```php
/**
 * @method static Vegetable BROCCOLI()
 * @method static Vegetable CABBAGE()
 */
class Vegetable extends GerritDrost\Lib\Enum\Enum
{
    const BROCCOLI = 'broccoli';
    const CABBAGE = 'cabbage';

    private $counter = 0;

    protected function construct()
    {
        $this->counter = 0;
    }

    public function increment()
    {
        $this->counter++;
    }

    public function getCounter()
    {
        return $this->counter;
    }
}

// Get the broccoli enum value
$broccoli = Vegetable::BROCCOLI();

// And another one. But this will actually be the same object because enum values are sort of singletons
$anotherBroccoli = Vegetable::BROCCOLI();

// And get some cabbage
$cabbage = Vegetable::CABBAGE();

// These calls should increment the same counter
$broccoli->increment();
$anotherBroccoli->increment();

// And this one a different one
$cabbage->increment();

// Should be 1
echo $cabbage->getCounter() . "\n";
// Should be 2
echo $broccoli->getCounter() . "\n";
// Should be 2
echo $anotherBroccoli->getCounter();

--- Output
1
2
2
```

### Value constructors
```php
/**
 * @method static Planet EARTH()
 * @method static Planet MARS()
 * @method static Planet VENUS()
 * @method static Planet JUPITER()
 */
class Planet extends GerritDrost\Lib\Enum\SimpleEnum
{
    const EARTH = 'Earth';
    const MARS = 'Mars';
    const VENUS = 'Venus';
    const JUPITER = 'Jupiter';

    private $g;
    private $radius;

    private function __EARTH()
    {
        $this->radius = 6371000;
        $this->g = 9.78033;
    }

    private function __MARS()
    {
        $this->radius = 3389500;
        $this->g = 3.7;
    }

    private function __VENUS()
    {
        $this->radius = 6051800;
        $this->g = 8.872;
    }

    private function __JUPITER()
    {
        $this->radius = 69911000;
        $this->g = 24.79;
    }

    public function getName()
    {
        // alias to the enum value
        return $this->getEnumValue();
    }

    public function getG()
    {
        return $this->g;
    }

    public function getRadius()
    {
        return $this->radius;
    }

    public function __toString()
    {
        return sprintf('%s(G: %0.2f, r: %dm)', $this->getName(), $this->getG(), $this->getRadius());
    }
}

echo implode("\n", Planet::getEnumInstances());

--- Output
Earth(G: 9.78, r: 6371000m)
Mars(G: 3.70, r: 3389500m)
Venus(G: 8.87, r: 6051800m)
Jupiter(G: 24.79, r: 69911000m)
```

## Are there future plans?
I want to look into possibilities to cache/compile enums so I don't have to rely on reflection at runtime anymore. I'm not sure how I want to approach it yet, I first need to find out which methods of caching/compiling will actually be an improvement and what their implications are.
