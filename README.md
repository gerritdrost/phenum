# Note
[PHP has native support for enums since version 8.1](https://www.php.net/manual/en/language.types.enumerations.php). Use those if you have the chance.

# phenum
Enums for PHP, nuff said.

## Description
A lot of people love enums. Unfortunately, PHP does not support them out-of-the-box, it requires `SplEnum` to be installed. I stumbled across this problems as well and decided to attempt to solve it: say hello to phenum.

## Why phenum?
Because it's easy to use and has some cool features. Enum values are actually are singleton objects, they have their own instance and can have their own variables! Also the library provides utility classes like the EnumMap which can be used to map values to Enums, something not possible with regular PHP arrays.

## Dependencies
PHP 8.1 or newer.

## Setup
Include [`gerritdrost/phenum`](https://packagist.org/packages/gerritdrost/phenum) using composer.

## Documentation
* [Getting Started](docs/getting-started.md)
* [Constructors](docs/constructors.md)
* [Example Usage](docs/example-usage.md)
* [EnumMap](docs/enum-map.md)
