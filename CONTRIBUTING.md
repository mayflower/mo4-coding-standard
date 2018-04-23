# Contributing

If you contribute code, please make sure it conforms to the
[PHPCS coding standard](https://github.com/squizlabs/PHP_CodeSniffer/blob/master/phpcs.xml.dist)
and that the unit tests still pass.
Whenever possible, add an auto fixer for coding standard violations. 

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

We use [PHPStan](https://github.com/phpstan/phpstan) and [Phan](https://github.com/phan/phan), please refer to the
respective documentation for installation instructions.

    ./vendor/phpstan analyse --level=max -c .phpstan.neon MO4/ tests/
    ./vendor/bin/phan -i

## Code Coverage

Make sure, that you write tests for your code.

Testing code coverage with [PHPUnit](https://phpunit.de/) requires [Xdebug](https://xdebug.org/) to be enabled.

You can generate a simple code coverage report by running in the repository root:

    ./vendor/bin/phpunit --coverage-text

In the case that Xdebug is disabled by default

     php -d zend_extension=xdebug.so vendor/bin/phpunit --coverage-text

will do the trick.

Please refer to the [PHPUnit Manual](https://phpunit.de/documentation.html) for further information about code coverage.

