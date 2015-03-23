<?php

namespace GerritDrost\Lib;

use ReflectionClass;

abstract class Enum
{

    /**
     * @var string
     */
    private $fqcn;

    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $name
     * @param mixed  $value
     */
    private final function __construct($fqcn, $name, $value)
    {
        $this->fqcn = $fqcn;
        $this->name = $name;
        $this->value = $value;
    }

    protected abstract function construct();

    /**
     * @param $enum
     *
     * @return bool
     */
    public final function equals($enum)
    {
        return (
            $enum instanceof Enum
            && $this->fqcn === $enum->fqcn
            && $this->name === $enum->name
        );
    }

    /**
     * @return string
     */
    public final function getEnumName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public final function getEnumValue()
    {
        return $this->value;
    }

    /**
     * @param string  $name
     * @param mixed[] $arguments
     *
     * @return static
     */
    public final static function __callStatic($name, $arguments)
    {
        // get the FQCN
        $fqcn = get_called_class();

        // Try to get the instance
        return self::getInstance($fqcn, $name);
    }

    /**
     * Array of enum instances. First dimension contains FQCNs, second dimension contains constant names
     *
     * @var Enum[][]
     */
    private static $instances = [];

    /**
     * @param string $fqcn FQCN of the enum
     * @param string $name Name of the constant
     *
     * @return static
     */
    private final static function getInstance($fqcn, $name)
    {
        if (!isset(self::$instances[$fqcn])) {
            self::loadClass($fqcn);
        }

        if (!isset(self::$instances[$fqcn][$name])) {
            return null;
        } else {
            return self::$instances[$fqcn][$name];
        }
    }

    /**
     * @param string $fqcn FQCN of the enum
     */
    private final static function loadClass($fqcn)
    {
        $reflectionClass = new ReflectionClass($fqcn);
        $constants       = $reflectionClass->getConstants();

        foreach ($constants as $name => $value) {
            $instance = new $fqcn($fqcn, $name, $value);

            /* @var $instance Enum */
            $instance->construct();

            $instanceConstructorName = '__' . $name;
            if (method_exists($instance, $instanceConstructorName)) {
                $reflectionMethod = $reflectionClass->getMethod($instanceConstructorName);
                $reflectionMethod->setAccessible(true);
                $reflectionMethod->invoke($instance);
            }

            if (!isset(self::$instances[$fqcn])) {
                self::$instances[$fqcn] = [];
            }

            self::$instances[$fqcn][$name] = $instance;
        }
    }
}