<?php

require __DIR__ . '/../vendor/autoload.php';

/**
 * @method static Vegetable BROCCOLI()
 * @method static Vegetable CABBAGE()
 */
class Vegetable extends GerritDrost\Lib\Enum
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