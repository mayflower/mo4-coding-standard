<?php
/**
 * Bootstrap file for PHP_CodeSniffer MO4 Coding Standard unit tests.
 *
 * @category PHP
 * @package  PHP_CodeSniffer-MO4
 * @author   Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @link     https://github.com/mayflower/mo4-coding-standard
 */

$myStandardName = 'MO4';

require_once __DIR__.'/../vendor/squizlabs/php_codesniffer/tests/bootstrap.php';

// Add this Standard.
PHP_CodeSniffer\Config::setConfigData(
    'installed_paths',
    __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$myStandardName,
    true
);

// Ignore all other Standards in tests.
$standards   = PHP_CodeSniffer\Util\Standards::getInstalledStandards();
$standards[] = 'Generic';

$ignoredStandardsStr = implode(
    ',',
    array_filter(
        $standards,
        function ($v) use ($myStandardName) {
            return $v !== $myStandardName;
        }
    )
);

putenv("PHPCS_IGNORE_TESTS={$ignoredStandardsStr}");
