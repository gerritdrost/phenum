# Enum value constructors

Because enum values are actually singletons and therefore objects, they are constructed at some point. To make use of this every enum can implement global and value-specific constructors. Note that these constructors officially aren't constructors. They are called right after the real constructor has been called, so maybe the technically more correct term would be "initializers". To the eye of the consuming developer however, they name constructor makes more sense.

## Global constructor
The so-called global constructor can be used by adding a `protected function __initEnum()`. This method is called after instantiation of the object and before the value-specific constructor. The alternative class `GerritDrost\Lib\Enum\SimpleEnum` features a default empty implementation for the global constructor and therefore does not require it to be implemented. You can see it being used in (Getting Started)[getting-started.md]. Below is an example of an enum facilitating the global constructor:
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

    protected function __initEnum()
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
```
Please note that the global constructor is still called for every enum value instance, it is not only called once!

Below is some code to see the Vegetable counter above in use. The code should be self-explanatory:

```php
// Get the broccoli enum value
$broccoli = Vegetable::BROCCOLI();

// And another one. But this will actually be the same object because enum values are singletons
$anotherBroccoli = Vegetable::BROCCOLI();

// And get some cabbage
$cabbage = Vegetable::CABBAGE();

// These calls should increment the same counter since both vars reference the same singleton
$broccoli->increment();
$anotherBroccoli->increment();

// And this one should reference a different singleton and therefore a different counter
$cabbage->increment();

echo $cabbage->getCounter() . "\n";
echo $broccoli->getCounter() . "\n";
echo $anotherBroccoli->getCounter();

--- Output
1
2
2
```

## Value constructor
Value constructors are constructors specific to one enum value. They must be `protected` and are named by prefixing the const name of the desired enum value with `__`. Value constructors are called after object instantiation and after the global constructor. An example:
```php
/**
 * @method static Planet MERCURY()
 * @method static Planet VENUS()
 * @method static Planet EARTH()
 * @method static Planet MARS()
 * @method static Planet JUPITER()
 * @method static Planet SATURN()
 * @method static Planet URANUS()
 * @method static Planet NEPTUNE()
 * @method static Planet PLUTO()
 */
class Planet extends GerritDrost\Lib\Enum\Enum
{
    const MERCURY = 'Mercury';
    const VENUS   = 'Venus';
    const EARTH   = 'Earth';
    const MARS    = 'Mars';
    const JUPITER = 'Jupiter';
    const SATURN  = 'Saturn';
    const URANUS  = 'Uranus';
    const NEPTUNE = 'Neptune';
    const PLUTO   = 'Pluto';

    private $g;
    private $radius;

    protected function __MERCURY()
    {
        $this->radius = 2439500;
        $this->g = 3.7;
    }

    protected function __VENUS()
    {
        $this->radius = 6051800;
        $this->g = 8.872;
    }

    protected function __EARTH()
    {
        $this->radius = 6371000;
        $this->g = 9.78033;
    }

    protected function __MARS()
    {
        $this->radius = 3389500;
        $this->g = 3.7;
    }

    protected function __JUPITER()
    {
        $this->radius = 69911000;
        $this->g = 24.79;
    }

    protected function __SATURN()
    {
        $this->radius = 60218000;
        $this->g = 9.0;
    }

    protected function __URANUS()
    {
        $this->radius = 25559000;
        $this->g = 8.7;
    }

    protected function __NEPTUNE()
    {
        $this->radius = 24764000;
        $this->g = 11.0;
    }

    protected function __PLUTO()
    {
        $this->radius = 1195000;
        $this->g = 0.6;
    }

    public function getName()
    {
        // alias to the const value
        return $this->getConstValue();
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
        return sprintf('%s(G: %0.3fm/s², r: %dm)', $this->getName(), $this->getG(), $this->getRadius());
    }
}
```
Now you can access them directly:
```php
echo Planet::MERCURY()->getRadius() . "\n";

---  Output
2439500
```

Or you can output all enum values them with this one-liner:
```php
echo implode("\n", Planet::getEnumValues());

--- Output
Mercury(G: 3.700m/s², r: 2439500m)
Venus(G: 8.872m/s², r: 6051800m)
Earth(G: 9.780m/s², r: 6371000m)
Mars(G: 3.700m/s², r: 3389500m)
Jupiter(G: 24.790m/s², r: 69911000m)
Saturn(G: 9.000m/s², r: 60218000m)
Uranus(G: 8.700m/s², r: 25559000m)
Neptune(G: 11.000m/s², r: 24764000m)
Pluto(G: 0.600m/s², r: 1195000m)
```
