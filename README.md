# MO4 CodeSniffer ruleset
 
Provides a PHP CodeSniffer ruleset for the MO4 coding standard

[![Build Status](https://travis-ci.org/mayflower/mo4-coding-standard.svg?branch=master)](https://travis-ci.org/mayflower/mo4-coding-standard)
[![Build Status](https://codecov.io/gh/mayflower/mo4-coding-standard/branch/master/graph/badge.svg)](https://codecov.io/gh/mayflower/mo4-coding-standard/branch/master/)
[![Scrutinizer Quality Level](https://scrutinizer-ci.com/g/mayflower/mo4-coding-standard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mayflower/mo4-coding-standard)
[![Maintainability](https://api.codeclimate.com/v1/badges/16114548a0315d993868/maintainability)](https://codeclimate.com/github/mayflower/mo4-coding-standard/maintainability)

[![Latest Stable Version](https://poser.pugx.org/mayflower/mo4-coding-standard/v/stable)](https://packagist.org/packages/mayflower/mo4-coding-standard)
[![Total Downloads](https://poser.pugx.org/mayflower/mo4-coding-standard/downloads)](https://packagist.org/packages/mayflower/mo4-coding-standard)
[![Latest Unstable Version](https://poser.pugx.org/mayflower/mo4-coding-standard/v/unstable)](https://packagist.org/packages/mayflower/mo4-coding-standard)
[![License](https://poser.pugx.org/mayflower/mo4-coding-standard/license)](https://packagist.org/packages/mayflower/mo4-coding-standard)
[![composer.lock](https://poser.pugx.org/mayflower/mo4-coding-standard/composerlock)](https://packagist.org/packages/mayflower/mo4-coding-standard)

## MO4 Coding Standard

The MO4 Coding Standard is an extension of the [Symfony Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html) and adds following rules:

* short array syntax `[...]` must be used instead of  `array(...)`
* in multi line arrays, the opening bracket must be followed by newline
* in multi line arrays, the closing bracket must be in own line
* in multi line arrays, the elements must be indented
* in associative arrays, the `=>` operators must be aligned
* in arrays, the key and `=>` operator must be on the same line
* each consecutive variable assignment must align at the assignment operator
* use statements must be sorted lexicographically, grouped by empty lines. The order function can be configured.
* you should use the imported class name when it was imported with a use statement
* interpolated variables in double quoted strings must be surrounded by `{ }`, e.g. `{$VAR}` instead of `$VAR`
* `sprintf` or `"{$VAR1} {$VAR2}"` must be used instead of the dot operator; concat operators are only allowed to
  concatenate constants and multi line strings
* a whitespace is required after each typecast, e.g. `(int) $value` instead of `(int)$value`
* doc blocks of class properties must be multiline and have exactly one `@var` annotation
* Multiline conditions must follow the [respective PEAR standard](https://pear.php.net/manual/en/standards.control.php#standards.control.splitlongstatements)
* There must be at least one space around operators, and (except for aligning multiline statements) at most one, see the
  [respective Squizlabs Sniff](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Customisable-Sniff-Properties#squizwhitespaceoperatorspacing)
  we imported with `ignoreNewlines = false`
* Single quotes must be used instead of double quoutes, where possible.

With this ruleset enabled, following [Symfony Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html) rules are not enforced:
* "`add doc blocks for all classes`": the doc block for classes can be omitted, if they add no value
* "`the license block has to be present at the top of every PHP file, before the namespace`": the license block can be omitted

Most of the issues can be auto-fixed with `phpcbf`.

## Requires

* [PHP](http://php.net) version 5.6 or later
* [Composer](https://getcomposer.org/) is optional, but strongly recommended

## Installation

### Composer

Using [Composer](https://getcomposer.org/) is the preferred way.

1. Add the MO4 coding standard to `composer.json`

        composer require --dev mayflower/mo4-coding-standard

2. Profit

        ./vendor/bin/phpcs --standard=MO4 path/to/my/file.php

3. Optionally, you might set MO4 as default coding standard

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

## Configuration

### MO4.Formatting.AlphabeticalUseStatements

The `order` property of the `MO4.Formatting.AlphabeticalUseStatements` sniff defines
which function is used for ordering.

Possible values for order:
* `dictionary` (default): based on [strcmp](http://php.net/strcmp), the namespace separator
  precedes any other character
  ```php
  use Doctrine\ORM\Query;
  use Doctrine\ORM\Query\Expr;
  use Doctrine\ORM\QueryBuilder;
  ```
* `string`: binary safe string comparison using [strcmp](http://php.net/strcmp)
  ```php
  use Doctrine\ORM\Query;
  use Doctrine\ORM\QueryBuilder;
  use Doctrine\ORM\Query\Expr;

  use ExampleSub;
  use Examples;
  ```
* `string-locale`: locale based string comparison using [strcoll](http://php.net/strcoll)
* `string-case-insensitive`: binary safe case-insensitive string comparison [strcasecmp](http://php.net/strcasecmp)
   ```php
   use Examples;
   use ExampleSub;
   ```

To change the sorting order for your project, add this snippet to your custom `ruleset.xml`:

```xml
<rule ref="MO4.Formatting.AlphabeticalUseStatements">
    <properties>
        <property name="order" value="string-locale"/>
    </properties>
</rule>
```

## Troubleshooting

If `phpcs` complains that MO4 is not installed, please check the installed coding standards with
`phpcs -i` and that `installed_paths` is set correctly with `phpcs --config-show`

## Dependencies

* [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) version 3.2.3 or later
* [David Joos's Symfony Coding Standard](https://github.com/djoos/Symfony-coding-standard) ruleset for PHP CodeSniffer
* [Composer installer for PHP_CodeSniffer coding standards](https://github.com/DealerDirect/phpcodesniffer-composer-installer)

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for information.

## License

This project is licensed under the MIT license.
See the [LICENSE](LICENSE) file for details.
