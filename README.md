# MO4 CodeSniffer ruleset
 
Provides a PHP CodeSniffer ruleset for the MO4 coding standard

[![Build Status](https://travis-ci.org/mayflower/mo4-coding-standard.svg?branch=master)](https://travis-ci.org/mayflower/mo4-coding-standard)
[![Code Coverage](https://codecov.io/gh/mayflower/mo4-coding-standard/branch/master/graph/badge.svg)](https://codecov.io/gh/mayflower/mo4-coding-standard/branch/master/)
[![Scrutinizer Quality Level](https://scrutinizer-ci.com/g/mayflower/mo4-coding-standard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mayflower/mo4-coding-standard)
[![Maintainability](https://api.codeclimate.com/v1/badges/16114548a0315d993868/maintainability)](https://codeclimate.com/github/mayflower/mo4-coding-standard/maintainability)

[![Latest Stable Version](https://poser.pugx.org/mayflower/mo4-coding-standard/v/stable)](https://packagist.org/packages/mayflower/mo4-coding-standard)
[![Total Downloads](https://poser.pugx.org/mayflower/mo4-coding-standard/downloads)](https://packagist.org/packages/mayflower/mo4-coding-standard)
[![Latest Unstable Version](https://poser.pugx.org/mayflower/mo4-coding-standard/v/unstable)](https://packagist.org/packages/mayflower/mo4-coding-standard)
[![License](https://poser.pugx.org/mayflower/mo4-coding-standard/license)](https://packagist.org/packages/mayflower/mo4-coding-standard)
[![composer.lock](https://poser.pugx.org/mayflower/mo4-coding-standard/composerlock)](https://packagist.org/packages/mayflower/mo4-coding-standard)

## MO4 Coding Standard

The MO4 Coding Standard is an extension of the [Symfony Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html) and adds following rules:

### MO4.Arrays.ArrayDoubleArrowAlignment
* In associative arrays, the `=>` operators must be aligned.
* In arrays, the key and `=>` operator must be on the same line.

### MO4.Arrays.MultiLineArray
* In multi line arrays, the opening bracket must be followed by newline.
* In multi line arrays, the closing bracket must be in own line.
* In multi line arrays, the elements must be indented.

### MO4.Commenting.PropertyComment
* doc blocks of class properties must be multiline and have exactly one `@var` annotation

### MO4.Formatting.AlphabeticalUseStatements
* `use` statements must be sorted lexicographically, grouped by empty lines. The order function can be configured.

#### Configuration
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

### MO4.Formatting.UnnecessaryNamespaceUsage
* The imported class name must be used, when it was imported with a `use` statement.

### MO4.Strings.VariableInDoubleQuotedString
* Interpolated variables in double quoted strings must be surrounded by `{ }`, e.g. `{$VAR}` instead of `$VAR`.

### Further rules (imported from other standards)
* See `MO4/ruleset.xml`, which has each imported rule commented.

Note that with this ruleset, the following [Symfony Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html) rules are not enforced:
* "`add doc blocks for all classes`": the doc block for classes can be omitted, if they add no value
* "`the license block has to be present at the top of every PHP file, before the namespace`": the license block can be omitted

Most of the issues can be auto-fixed with `phpcbf`.

## Requires

* [PHP](http://php.net) version 7.1 or later
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

## Troubleshooting

If `phpcs` complains that MO4 is not installed, please check the installed coding standards with
`phpcs -i` and that `installed_paths` is set correctly with `phpcs --config-show`

## Dependencies

* [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) version 3.5 or later
* [David Joos's Symfony Coding Standard](https://github.com/djoos/Symfony-coding-standard) version 3.0 or later
* [Composer installer for PHP_CodeSniffer coding standards](https://github.com/DealerDirect/phpcodesniffer-composer-installer)
* [Slevomat Coding Standard](https://github.com/slevomat/coding-standard) version 4.8.5 or later

## Contributing

See [CONTRIBUTING.md](.github/CONTRIBUTING.md) for information.

## License

This project is licensed under the MIT license.
See the [LICENSE](LICENSE) file for details.
