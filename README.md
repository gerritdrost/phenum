# phenum
Enums for PHP, nuff said

## Description
A lot of people love enums. Unfortunately, PHP does not support them out-of-the-box, it requires `SplEnum` to be installed. However, that unfortunately is often not an option because you can't install it on one of your target environments. Also, SplEnum sort of is a limited implementation of Enum.

I stumbled across these problems as well and decided to attempt to solve them: say hello to phenum.

## Why phenum?
First of all, you don't need SplEnum! The only thing you need is composer, which is a common thing nowadays.

Secondly, *phenum* not only allows enumerations, the enum values are also actually objects! This means that the name and value of the constant are provided through the object, there is an `equals()` method and the enum can have properties that are global or value-dependant.

## Are there downsides?
Yup, unfortunately there are some. First of all, to have type hinting you'll require some phpdoc method hinting. Secondly, *phenum* makes use of `ReflectionClass` and `ReflectionMethod` for the creating of the enum instances, which as you might know, is kind of expensive. Still, since it is only done moderately, stuff won't immediately explode. Just wanted to let you know.

## Are there future plans?
I want to look into possibilities to cache/compile enums so the reflection downsides disappear. I'm not sure how I want to approach it yet, I first need to find out which methods will actually be an improvement and what the implications are for developers using this. *Phenum* should remain easy to use after all :)

## How do I use it?

### Example
Actually, it's pretty self-explanatory:
```php

/**
 * @method static FoobarEnum FOO()
 * @method static FoobarEnum BAR()
 */
class FoobarEnum extends GerritDrost\Lib\Enum
{
    const FOO = 0;
    const BAR = 1;

    private $foobar;
    private $constructed = false;

    private function __FOO() {
        $this->foobar = 'foo';
    }

    private function __BAR() {
        $this->foobar = 'bar';
    }

    public function getFoobar()
    {
        return $this->foobar;
    }

    protected function construct()
    {
        $this->constructed = true;
    }
}

// Notice the function brackets at the end, we are secretly calling methods here
$foo = FoobarEnum::FOO();
$bar = FoobarEnum::BAR();

print_r($foo);
print_r($bar);
--- Output
FoobarEnum Object
(
    [foobar:FoobarEnum:private] => foo
    [constructed:FoobarEnum:private] => 1
    [fqcn:GerritDrost\Lib\Enum:private] => FoobarEnum
    [name:GerritDrost\Lib\Enum:private] => FOO
    [value:GerritDrost\Lib\Enum:private] => 0
) 
FoobarEnum Object
(
    [foobar:FoobarEnum:private] => bar
    [constructed:FoobarEnum:private] => 1
    [fqcn:GerritDrost\Lib\Enum:private] => FoobarEnum
    [name:GerritDrost\Lib\Enum:private] => BAR
    [value:GerritDrost\Lib\Enum:private] => 1
)
```

### Are the constructors obligatory?
You can always leave out the instance specific constructors like `protected function __FOO()` or `protected  function __BAR()`. They are detected through reflection and are simply ignored if not present. If you don't want to use the global constructor, extend from `GerritDrost\Lib\SimpleEnum` instead of `GerritDrost\Lib\Enum`. Easy as that :)
