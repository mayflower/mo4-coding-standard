# MO4 CodeSniffer ruleset <a href="https://travis-ci.org/mayflower/mo4-coding-standard/"><img src="https://secure.travis-ci.org/mayflower/mo4-coding-standard.png?branch=master"></a>

Provides a CodeSniffer ruleset

* MO4 standard

Requires

* [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [Symfony Coding Standard](https://github.com/djoos/Symfony-coding-standard)

## MO4 Coding Standard

The MO4 Coding Standard is an extension of the [Symfony Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html) and adds following rules:

* short array syntax `[...]` must be used instead of  `array(...)`
* in associative arrays, the `=>` operators must be aligned
* in arrays, the key and `=>` operator must be on the same line
* each consecutive variable assignment must align at the assignment operator
* use statements must be sorted lexicographically
* you should use the imported class name when it was imported with a use statement
* interpolated variables in double quoted strings must be surrounded by `{ }`, e.g. `{$VAR}` instead of `$VAR`
* `sprintf` or `"{$VAR1} {$VAR2}"` must be used instead of the dot operator; concat operators are only allowed to concatenate constants and multi line strings,
* a whitespace is required after each typecast, e.g. `(int) $value` instead of `(int)$value`


## Installation

1. Install phpcs:

        pear install PHP_CodeSniffer

2. Find your PEAR directory:

        pear config-show | grep php_dir

3. Copy, symlink or check out the Symfony coding standard and this repository to their respecting folders inside the
   phpcs `Standards` directory:

        cd /path/to/pear/PHP/CodeSniffer/src/Standards
        git clone https://github.com/djoos/Symfony-coding-standard.git Symfony
        mv Symfony/Symfony/* Symfony/
        git clone https://github.com/mayflower/mo4-coding-standard.git MO4

4. Select the MO4 ruleset as your default coding standard:

        phpcs --config-set default_standard MO4

5. Profit

        phpcs path/to/my/file.php

## Contributing

If you contribute code to these sniffs, please make sure it conforms to the PHPCS coding standard and that the unit tests still pass.

To check the coding standard, run in the repository root:

        bin/phpcs --ignore='*/vendor/*'

The unit-tests are run from within the PHP_CodeSniffer directory

* clone the [CodeSniffer repository](https://github.com/squizlabs/PHP_CodeSniffer)
* symlink, copy or clone the [Symfony Coding Standard](https://github.com/djoos/Symfony-coding-standard) and symlink, copy or move its `Symfony` subdirectory to `src/Standards/Symfony`
* symlink, copy or clone this repository to `src/Standards/MO4`
* from the CodeSniffer repository root run `phpunit --filter MO4`

## Credit


## License

This project is licensed under the MIT license.
