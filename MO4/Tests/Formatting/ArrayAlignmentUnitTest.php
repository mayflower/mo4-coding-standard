<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer-MO4
 * @author   Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @version  GIT: master
 * @link     https://github.com/Mayflower/mo4-coding-standard
 */

/**
 * Unit test class for the ArrayValueAlignment sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */
namespace MO4\Tests\Formatting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

class ArrayAlignmentUnitTest extends AbstractSniffUnitTest
{


    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $testFile test file
     *
     * @return array(int => int)
     */
    protected function getErrorList($testFile='')
    {
        switch ($testFile) {
        case 'ArrayAlignmentUnitTest.pass.inc':
            return array();
        case 'ArrayAlignmentUnitTest.fail.inc':
            return array(
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
                    145 => 1,
                    151 => 2,
                    155 => 1,
                   );
        }//end switch

        return null;

    }//end getErrorList()


    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array(int => int)
     */
    protected function getWarningList()
    {
        return array();

    }//end getWarningList()


}//end class
