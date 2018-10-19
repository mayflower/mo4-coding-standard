<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/mayflower/mo4-coding-standard
 */

namespace MO4\Tests\Commenting;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the AlphabeticalUseStatements sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2014 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
class PropertyCommentUnitTest extends AbstractSniffUnitTest
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
    protected function getErrorList($testFile='')
    {
        switch ($testFile) {
        case 'PropertyCommentUnitTest.pass.inc':
            return [];
        case 'PropertyCommentUnitTest.fail.inc':
            return [
                7  => 1,
                10 => 1,
                17 => 1,
                26 => 2,
                29 => 1,
                34 => 1,
                37 => 2,
                41 => 1,
                44 => 1,
            ];
        }

        throw new RuntimeException("Testfile {$testFile} in ".__DIR__.' is not handled by '.__CLASS__);

    }//end getErrorList()


    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @return array<int, int>
     */
    protected function getWarningList()
    {
        return [];

    }//end getWarningList()


}//end class
