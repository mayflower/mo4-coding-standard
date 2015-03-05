# MO4 CodeSniffer ruleset <a href="https://travis-ci.org/Mayflower/mo4-coding-standard/"><img src="https://secure.travis-ci.org/Mayflower/mo4-coding-standard.png?branch=master"></a>

Provides a CodeSniffer ruleset

* MO4 standard

Requires

* [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [Symfony Coding Standard](https://github.com/xalopp/symfony-coding-standard)

##MO4 Coding Standard

The MO4 Coding Standard is an extension of the [Symfony Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html) and adds following rules:

* short tags "[...]" must be used instead of  "array(...)",
* in associates arrays, the "=>" operators must be aligned,
* in arrays, the key and '=>' operator must be on the same line.
* each consecutive variable assignements must align on the assigment operator,
* use statements must be sorted,
* you should use the imported class name, whenever it was imported with a use statement,
* variables in double quoted strings must be surrounded by { }, e.g. "{$VAR}" instead of "$VAR",
* sprintf or "{$VAR1} {$VAR2}" must be used instead of the concat operator, concat opetor are only allowed to concat constant an multi line strings,
* a white space is requried after each cast opetor, e.g. "(int) $value" instead of "(int)$value",


## Installation

1. Install phpcs:

        pear install PHP_CodeSniffer

2. Find your PEAR directory:

        pear config-show | grep php_dir

3. Copy, symlink or check out this repo to a folder called Symfony inside the
   phpcs `Standards` directory:

        cd /path/to/pear/PHP/CodeSniffer/Standards
        git clone git://github.com/xalopp/symfony-coding-standard.git Symfony
        git clone git://github.com/Mayflower/mo4-coding-standard.git MO4

4. Select the Symfony ruleset as your default coding standard:

        phpcs --config-set default_standard MO4

5. Profit

        phpcs path/to/my/file.php

## Contributing

If you contribute code to these sniffs, please make sure it conforms to the PHPCS coding standard and that the unit tests still pass.

To check the coding standard, run from the Symfony-coding-standard source root:

        phpcs --ignore=Tests --standard=PHPCS . -n

The unit-tests are run from within the PHP_CodeSniffer directory

* get the [CodeSniffer repository](https://github.com/squizlabs/PHP_CodeSniffer)
* get the [Symfony Coding Standard](https://github.com/xalopp/symfony-coding-standard) and symlink, copy or clone it at CodeSniffer/Standards/Symfony
* symlink, copy or clone this repository at CodeSniffer/Standards/MO4
* from the CodeSniffer repository root run `phpunit --filter Symfony_ tests/AllTests.php`

## Credit


## Licence

This project is licensed under the MIT license.
