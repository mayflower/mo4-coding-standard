# Contributing

## Setup

We do recommend the following setup:

* make sure that [Composer](https://getcomposer.org) is installed
* clone the [PHP_CodeSniffer repository](https://github.com/squizlabs/PHP_CodeSniffer)
* run `composer install` inside the `PHP_CodeSniffer` directory to install required dependencies
* get the [Symfony Coding Standard](https://github.com/djoos/Symfony-coding-standard) and symlink, copy or move its `Symfony` subdirectory to `PHP_CodeSniffer/src/Standards/Symfony` 
* symlink, copy or clone this repository to `PHP_CodeSniffer/src/Standards/MO4`

        git clone https://github.com/squizlabs/PHP_CodeSniffer.git
        cd PHP_CodeSniffer
        composer install
        cd src/Standards
        git clone https://github.com/djoos/Symfony-coding-standard.git Symfony
        mv Symfony/Symfony/* Symfony/
        git clone https://github.com/mayflower/mo4-coding-standard.git MO4


## Coding Standard and Tests

If you contribute code, please make sure it conforms to the PHPCS coding standard and that the unit tests still pass.

1. To check the coding standard, run in the repository root of `PHP_CodeSniffer`:

        ./bin/phpcs --ignore='*/vendor/*' src/Standards/MO4

2. The unit-tests are run from within the `PHP_CodeSniffer` directory

        ./vendor/bin/phpunit --filter MO4

