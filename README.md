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
* doc blocks of class properties must be multiline and have exactly one `@var` annotation

## Installation

### Composer

Using [Composer](https://getcomposer.org/) is the preferred way.

1. Add the MO4 repository

        composer config repositories.mo4 vcs https://github.com/mayflower/mo4-coding-standard.git

   If composer complains about a missing composer.json, just create an empty one

        echo '{}' > composer.json

2. Add the MO4 coding standard to `composer.json`

        composer require --dev mayflower/mo4-coding-standard:dev-master

3. Profit

        ./vendor/bin/phpcs --standard=MO4 path/to/my/file.php

4. Optionally, you might set MO4 as default coding standard

        ./vendor/bin/phpcs --config-set default_standard MO4

### Source

1. Checkout this repository

        git clone https://github.com/mayflower/mo4-coding-standard.git

2. Install dependencies

        composer install

3. Check, that Symfony and MO4 are listed as coding standards

        ./vendor/bin/phpcs -i

4. Profit

        ./vendor/bin/phpcs --standard=MO4 path/to/my/file.php

5. Optionally, you might set MO4 as default coding standard

        ./vendor/bin/phpcs --config-set default_standard MO4


### Pear

1. Install phpcs

        pear install PHP_CodeSniffer

2. Check out the Symfony coding standard and this repository

        git clone https://github.com/djoos/symfony-coding-standard.git
        git clone https://github.com/mayflower/mo4-coding-standard.git

3. Select the MO4 ruleset as your default coding standard

        phpcs --config-set installed_paths PATH/TO/symfony2-coding-standard,PATH/TO/mo4-coding-standard
        phpcs --config-set default_standard MO4

4. Profit

        phpcs --standard=MO4 path/to/my/file.php

5. Optionally, you might set MO4 as default coding standard

        phpcs --config-set default_standard MO4

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for information.

## Credit

## License

This project is licensed under the MIT license.
