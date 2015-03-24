[![Build Status](https://travis-ci.org/gerritdrost/phenum.svg?branch=master)](https://travis-ci.org/gerritdrost/phenum)
 [![Coverage Status](https://coveralls.io/repos/gerritdrost/phenum/badge.svg)](https://coveralls.io/r/gerritdrost/phenum)
# phenum
Enums for PHP, nuff said.

## Description
A lot of people love enums. Unfortunately, PHP does not support them out-of-the-box, it requires `SplEnum` to be installed. I stumbled across this problems as well and decided to attempt to solve it: say hello to phenum.

## Why phenum?
Because it's easy to use and has some cool features. Enum values are actually are singleton objects, they have their own instance and can have their own variables! Also the library provides utility classes like the EnumMap which can be used to map values to Enums, something not possible with regular PHP arrays.

## Dependencies
PHP 5.5 or newer or HHVM and of course composer.

## Setup
Include [`gerritdrost/phenum`](https://packagist.org/packages/gerritdrost/phenum) using composer.

## Documentation
You can find the latest documentation in the [docs folder](docs/readme.md).

## Are there future plans?
I want to look into possibilities to cache/compile enums so I don't have to rely on reflection at runtime anymore. I'm not sure how I want to approach it yet, I first need to find out which methods of caching/compiling will actually be an improvement and what their implications are.
