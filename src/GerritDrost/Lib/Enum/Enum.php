<?php

namespace GerritDrost\Lib\Enum;

use ReflectionClass;

abstract class Enum
{

    /**
     * @var string FQCN of the extending enum class
     */
    private $fqcn;

    /**
     * @var string name of the const representing this enum value
     */
    private $name;

    /**
     * @var mixed value of the const representing this enum value
     */
    private $value;

    /**
     * Array of enum instances. First dimension contains FQCNs, second dimension contains constant names
     *
     * @var Enum[][]
     */
    private static $instances = [];

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

    /**
     * Constructor method that is called once for all value singletons
     */
    protected abstract function construct();

    /**
     * Returns true if the provided value is the same enum (same class and same constant)
     *
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
     * @return string returns the name of the const representing this enum value
     */
    public final function getConstName()
    {
        return $this->name;
    }

    /**
     * @return mixed returns the value of the const representing this enum value
     */
    public final function getConstValue()
    {
        return $this->value;
    }

    /**
     * Returns the enum value instance representing the provided const name or null when the const name is not present in
     * the enum.
     *
     * @param string $enumName
     *
     * @return Enum
     */
    public static final function byName($enumName)
    {
        $instances = self::getInstances(get_called_class());

        return isset($instances[$enumName])
            ? $instances[$enumName]
            : null;
    }

    /**
     * Returns all value singletons of this enum
     *
     * @return Enum[]
     */
    public static final function getEnumInstances()
    {
        return self::getInstances(get_called_class());
    }

    /**
     * This actually handles the ::FOO()-like calls to enum classes. Returns the right value singleton or null.
     *
     * @param string  $name
     * @param mixed[] $arguments
     *
     * @return static|null
     */
    public final static function __callStatic($name, $arguments)
    {
        // get the FQCN
        $fqcn = get_called_class();

        // Try to get the instance
        return self::getInstance($fqcn, $name);
    }

    /**
     * Returns the value singleton for the provided fqcn/name combination or null when the fqcn/name combo is invalid
     *
     * @param string $fqcn FQCN of the enum
     * @param string $name Name of the constant
     *
     * @return static|null
     */
    private final static function getInstance($fqcn, $name)
    {
        $instances = self::getInstances($fqcn);

        if (!isset($instances[$name])) {
            return null;
        } else {
            return $instances[$name];
        }
    }

    /**
     * Returns the array of all value singletons of one Enum class
     *
     * @param string $fqcn FQCN of the enum
     *
     * @return Enum[]
     */
    private final static function &getInstances($fqcn)
    {
        if (!isset(self::$instances[$fqcn])) {
            self::loadClass($fqcn);
        }

        return self::$instances[$fqcn];
    }

    /**
     * Uses reflection to load and cache all value singletons of the class represented by the fqcn.
     *
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
            if (is_callable([$instance, $instanceConstructorName])) {
                $instance->$instanceConstructorName();
            }

            if (!isset(self::$instances[$fqcn])) {
                self::$instances[$fqcn] = [];
            }

            self::$instances[$fqcn][$name] = $instance;
        }
    }
}
