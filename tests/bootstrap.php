<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * Bootstrap file for PHP_CodeSniffer MO4 Coding Standard unit tests.
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @license http://spdx.org/licenses/MIT MIT License
 *
 * @link    https://github.com/mayflower/mo4-coding-standard
 */

declare(strict_types=1);

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
        static function ($v) use ($myStandardName): bool {
            return $v !== $myStandardName;
        }
    )
);

putenv("PHPCS_IGNORE_TESTS={$ignoredStandardsStr}");

/*
 * PHPUnit 9.3 is the first version which supports Xdebug 3, but we're using older versions.
 *
 * For now, until a fix is pulled into the whole stack, this will allow older PHPUnit
 * versions to run with Xdebug 3 for code coverage.
 */

if ((true === \extension_loaded('xdebug')) && (true === \version_compare((string) \phpversion('xdebug'), '3', '>='))) {
    if (false === defined('XDEBUG_CC_UNUSED')) {
        define('XDEBUG_CC_UNUSED', null);
    }

    if (false === defined('XDEBUG_CC_DEAD_CODE')) {
        define('XDEBUG_CC_DEAD_CODE', null);
    }
}
