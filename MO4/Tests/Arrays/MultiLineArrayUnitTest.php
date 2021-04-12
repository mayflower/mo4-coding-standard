<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @license http://spdx.org/licenses/MIT MIT License
 *
 * @link    https://github.com/mayflower/mo4-coding-standard
 */

declare(strict_types=1);

namespace MO4\Tests\Arrays;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for @see MultiLineArraySniff
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2013-2017 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
class MultiLineArrayUnitTest extends AbstractSniffUnitTest
{
    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $testFile test file
     *
     * @return array<int, int>
     *
     * @throws RuntimeException
     */
    protected function getErrorList(string $testFile = ''): array
    {
        switch ($testFile) {
            case 'MultiLineArrayUnitTest.pass.inc':
                return [];
            case 'MultiLineArrayUnitTest.fail.inc':
                return [
                    4  => 1,
                    12 => 1,
                    18 => 2,
                    22 => 1,
                    24 => 1,
                    28 => 1,
                    32 => 1,
                ];
        }

        throw new RuntimeException(
            \sprintf('%s%s is not handled by %s', \sprintf('Testfile %s in ', $testFile), __DIR__, self::class)
        );
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     */
    protected function getWarningList(): array
    {
        return [];
    }
}
