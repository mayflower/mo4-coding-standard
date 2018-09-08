<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/mayflower/mo4-coding-standard
 */

namespace MO4\Tests\Formatting;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the AlphabeticalUseStatements sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013-2017 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
class AlphabeticalUseStatementsUnitTest extends AbstractSniffUnitTest
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
     * @throws RuntimeException
     */
    protected function getErrorList(string $testFile=''): array
    {
        switch ($testFile) {
        case 'AlphabeticalUseStatementsUnitTest.pass.inc':
        case 'AlphabeticalUseStatementsUnitTest.pass.1.inc':
            return [];
        case 'AlphabeticalUseStatementsUnitTest.fail.1.inc':
            return [
                4  => 1,
                5  => 1,
                8  => 1,
                9  => 1,
                12 => 1,
            ];
        // Take care, more than one fix will be applied.
        case 'AlphabeticalUseStatementsUnitTest.fail.2.inc':
            return [
                6 => 1,
                8 => 1,
            ];
        case 'AlphabeticalUseStatementsUnitTest.fail.3.inc':
            return [
                7  => 1,
                8  => 1,
                10 => 1,
                15 => 1,
            ];
        case 'AlphabeticalUseStatementsUnitTest.fail.4.inc':
            return [
                4  => 1,
                8  => 1,
                13 => 1,
                17 => 1,
                20 => 1,
                21 => 1,
            ];
        case 'AlphabeticalUseStatementsUnitTest.fail.6.inc':
            return [5 => 1];
        }//end switch

        throw new RuntimeException("Testfile {$testFile} in ".__DIR__.' is not handled by '.self::class);

    }//end getErrorList()


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

    }//end getWarningList()


}//end class
