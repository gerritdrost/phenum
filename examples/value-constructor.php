<?php

require __DIR__ . '/../vendor/autoload.php';

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

    protected function __VENUS()
    {
        $this->radius = 6051800;
        $this->g = 8.872;
    }

    protected function __JUPITER()
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

echo Planet::EARTH() . "\n";

echo implode("\n", Planet::getEnumInstances());
