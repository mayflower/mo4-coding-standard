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

namespace MO4\Tests;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Abstract class to make the writing of tests more convenient.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard.
 *
 * Expected errors and warnings are stored in the class fields $expectedErrorList
 * and $expectedWarningList
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2013-2021 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
abstract class AbstractMo4SniffUnitTest extends AbstractSniffUnitTest
{
    /**
     * Array or Array containing the test file as key and as value the key-value pairs with line number and number of#
     * errors as describe in @see AbstractSniffUnitTest::getErrorList
     *
     * When the array is empty, the test will pass.
     *
     * @var array
     */
    protected $expectedErrorList = [];

    /**
     * Array or Array containing the test file as key and as value the key-value pairs with line number and number of#
     * errors as describe in @see AbstractSniffUnitTest::getWarningList
     *
     * When the array is empty, the test will pass.
     *
     * @var array
     */
    protected $expectedWarningList = [];

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
    #[\Override]
    protected function getErrorList(string $testFile = ''): array
    {
        return $this->getRecordForTestFile($testFile, $this->expectedErrorList);
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $testFile test file
     *
     * @return array<int, int>
     *
     * @throws RuntimeException
     */
    #[\Override]
    protected function getWarningList(string $testFile = ''): array
    {
        return $this->getRecordForTestFile($testFile, $this->expectedWarningList);
    }

    /**
     * Returns the lines where warnings should occur for the error or warning list.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $testFile test file
     * @param array  $list     record for test file
     *
     * @return array<int, int>
     *
     * @throws RuntimeException
     */
    private function getRecordForTestFile(string $testFile, array $list): array
    {
        if ([] === $list) {
            return [];
        }

        if (!\array_key_exists($testFile, $list)) {
            throw new RuntimeException(
                \sprintf('%s%s is not handled by %s', \sprintf('Testfile %s in ', $testFile), __DIR__, self::class)
            );
        }

        return $list[$testFile];
    }
}
