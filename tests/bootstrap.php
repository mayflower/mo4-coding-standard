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

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Util\Standards;

$myStandardName = 'MO4';

require_once __DIR__.'/../vendor/squizlabs/php_codesniffer/tests/bootstrap.php';

// Add this Standard.
Config::setConfigData(
    'installed_paths',
    __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$myStandardName,
    true
);

// Ignore all other Standards in tests.
$standards   = Standards::getInstalledStandards();
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
