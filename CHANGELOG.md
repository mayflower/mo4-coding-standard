# Changelog

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [3.2.2] - 2019-08-29
### Changed
- Add `@group` to default `ignoredAnnotationNames`
- Add `ignoreMultiline` for if vs. ternary

## [3.2.1] - 2019-07-01
### Changed
- Raised minimum Symfony CS release
- Use strict typing in MO4 sniff code

## [3.2.0] - 2019-04-23
### Changed
-  Raised minimum Symfony CS release

## [3.1.0] - 2019-04-14
### Changed
-  Raised minimum Symfony CS release
-  Raised minimum Slevomat CS release

## [3.0.1] - 2018-11-23
### Changed
- Updated dealerdirect/phpcodesniffer-composer-installer dependency

## [3.0.0] - 2018-10-24
### Added
- PHP 7.3 support
- Windows support
- Integration tests
- New sniffs from upstream rulesets, see comments in [the ruleset](https://github.com/mayflower/mo4-coding-standard/blob/master/MO4/ruleset.xml)
- Autofixer for `MO4.Arrays.ArrayDoubleArrowAlignment`, fixes [#91](https://github.com/mayflower/mo4-coding-standard/issues/91)

### Changed
- Raised PHP requirement to 7.1
- Detection of more comments after declarations, fixes [#96](https://github.com/mayflower/mo4-coding-standard/issues/96)
- Documentation about upstream sniffs moved from README to comments per rule in [the ruleset](https://github.com/mayflower/mo4-coding-standard/blob/master/MO4/ruleset.xml)

### Removed
- Support for PHP 5.6 and PHP 7.0
- PEAR as installation method

## [2.1.2] - 2018-09-06
### Changed
- prefer builtin-functions for performance

## [2.1.1] - 2018-05-17
### Changed
- Check for empty needle when calling strpos, fixes [#78](https://github.com/mayflower/mo4-coding-standard/issues/78)

## [2.1.0] - 2018-04-06
### Changed
- PHPCS version 3.2.3 or later is required.

## [2.0.0] - 2017-12-18
### Added
- `MO4.Arrays.ArrayDoubleArrowAlignment`
- `MO4.Arrays.MultiLineArray`
- `Generic.Arrays.ArrayIndent`
- `Squiz.WhiteSpace.OperatorSpacing`
- The behaviour of `MO4.Formatting.AlphabeticalUseStatements` can be configured with the property `order`.
  Possible values are: `dictionary`, `string`, `string-locale` or `string-case-insensitive`.
- Static code analysis with PHPStan.
- Various cloud based code quality tools like: Scrutinizer CI, codecov.io, ...

### Changed
- PHPCS version 3.2.0 or later is required.
- Code complies to PHPCS coding standard version 3.2.0.
- Default ordering of `MO4.Formatting.AlphabeticalUseStatements` is now `dictionary` instead of `string`.
- Updates and fixes for class documentation.
- Improve testing and code coverage.
- Unknown test files will not trigger wrong type exceptions anymore, but report decent error messages with `RuntimeException`.
- Many fixes and stability improvements.

### Removed
- `MO4.Formatting.ArrayAlignmentUnit`
  - replaced by `MO4.Arrays.ArrayDoubleArrowAlignment` and `MO4.Arrays.MultiLineArray`.
- `MO4.Formatting.UseArrayShortTag`
  - replaced by `Generic.Arrays.DisallowLongArray`.
- Dead code from `MO4.Strings.VariableInDoubleQuotedString`.

## [1.0.0] - 2017-11-20
### Changed
- MO4 coding standard can be installed as composer package and is released on packagist.org.
- Replaced underlying Symfony coding standard.
- PHPCS 3.0 or later is required.
