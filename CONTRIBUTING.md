# Contributing

If you contribute code, please make sure it conforms to the
[PHPCS coding standard](https://github.com/squizlabs/PHP_CodeSniffer/blob/master/phpcs.xml.dist)
and that the unit tests still pass.

## Setup

We do recommend the following setup:

* make sure that [Composer](https://getcomposer.org) is installed
* clone this repository

        git clone https://github.com/mayflower/mo4-coding-standard.git

* install all required dependencies

        composer install
        
* be sure that [Xdebug](https://xdebug.org/) is installed, if you like to check code coverage.


## Coding Standard

To check the coding standard, execute in the repository root:

    ./vendor/bin/phpcs

`phpcs` might report that some coding standard issues can be fixed automatically.
So give `phpcbf` a try and let it fix the issues for you:

    ./vendor/bin/phpcbf

## Tests

To run the unit tests, execute in the repository root:

    ./vendor/bin/phpunit
    
## Static analysis

We do recommend to use [PHPStan](https://github.com/phpstan/phpstan) for static analysis, with maximum inspection level.
Please refer to the [PHPStan](https://github.com/phpstan/phpstan#installation) documentation for
installation instructions.

    phpstan analyse --level=max -c .phpstan.neon MO4/ tests/

## Code Coverage

Make sure, that you write tests for your code.

Testing code coverage with [PHPUnit](https://phpunit.de/) requires [Xdebug](https://xdebug.org/) to be enabled.

You can generate a simple code coverage report by running in the repository root:

    ./vendor/bin/phpunit --coverage-text

In the case that Xdebug is disabled by default

     php -d zend_extension=xdebug.so vendor/bin/phpunit --coverage-text

will do the trick.

Please refer to the [PHPUnit Manual](https://phpunit.de/documentation.html) for further information about code coverage.

