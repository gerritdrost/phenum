<?php

require __DIR__ . '/../vendor/autoload.php';

/**
 * @method static Planet EARTH()
 * @method static Planet MARS()
 * @method static Planet VENUS()
 * @method static Planet JUPITER()
 */
class Planet extends GerritDrost\Lib\SimpleEnum
{
    const EARTH = 'Earth';
    const MARS = 'Mars';
    const VENUS = 'Venus';
    const JUPITER = 'Jupiter';

    private $g;
    private $radius;

    public function __EARTH()
    {
        $this->radius = 6371000;
        $this->g = 9.78033;
    }

    public function __MARS()
    {
        $this->radius = 3389500;
        $this->g = 3.7;
    }

    public function __VENUS()
    {
        $this->radius = 6051800;
        $this->g = 8.872;
    }

    public function __JUPITER()
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