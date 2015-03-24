# Enum value constructors

Because enum values are actually singletons and therefore objects, they are constructed at some point. To make use of this
every enum can implement global and value-specific constructors.

## Global constructor
The so-called global constructor can be used by extending `GerritDrost\Lib\Enum\Enum` and implementing its abstract `protected function construct()`. The constructor method is called after instantiation of the object and before the value-specific constructor. The alternative class `GerritDrost\Lib\Enum\SimpleEnum` features a default empty implementation for the global constructor and therefore does not require it to be implemented. You can see it being used in (Getting Started)[getting-started.md]. Below is an example of an enum facilitating the global constructor:
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
```
Please note that the global constructor is still called for every enum value instance, it is not only called once!

Below is some code to see the Vegetable counter above in use. The code should be self-explanatory:

```php
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

echo $cabbage->getCounter() . "\n";
echo $broccoli->getCounter() . "\n";
echo $anotherBroccoli->getCounter();

--- Output
1
2
2
```