# Contributing

## Setup

We do recommend the following setup:

* make sure that [Composer](https://getcomposer.org) is installed
* clone this repository

        git clone https://github.com/mayflower/mo4-coding-standard.git

* install all required dependencies

        composer install


## Coding Standard and Tests

If you contribute code, please make sure it conforms to the PHPCS coding standard and that the unit tests still pass.

1. To check the coding standard, execute in the repository root:

        ./vendor/bin/phpcs

2. To run the unit tests, execute in the repository root:

        ./vendor/bin/phpunit --filter MO4

