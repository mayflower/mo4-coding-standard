<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/mayflower/mo4-coding-standard
 */
declare(strict_types=1);

namespace MO4\Tests\Arrays;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for @see ArrayDoubleArrowAlignmentSniff
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013-2017 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
class ArrayDoubleArrowAlignmentUnitTest extends AbstractSniffUnitTest
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
        case 'ArrayDoubleArrowAlignmentUnitTest.pass.inc':
            return [];
        case 'ArrayDoubleArrowAlignmentUnitTest.fail.inc':
            return [
                5   => 1,
                10  => 1,
                17  => 2,
                18  => 2,
                22  => 1,
                28  => 1,
                38  => 1,
                43  => 1,
                45  => 1,
                49  => 1,
                51  => 1,
                58  => 1,
                59  => 1,
                61  => 1,
                67  => 1,
                70  => 1,
                71  => 1,
                73  => 1,
                82  => 1,
                83  => 1,
                85  => 1,
                93  => 1,
                94  => 1,
                97  => 1,
                105 => 1,
                130 => 1,
                132 => 1,
                134 => 1,
                136 => 2,
                140 => 1,
                141 => 1,
                145 => 2,
                149 => 1,
            ];
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
