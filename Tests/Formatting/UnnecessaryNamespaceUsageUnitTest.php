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
 * Unit test class for the UnnecessaryNamespaceUsageUnitTest sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @author    Marco Jantke <marco.jantke@gmail.com>
 * @author    Steffen Ritter <steffenritter1@gmail.com>
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */
class MO4_Tests_Formatting_UnnecessaryNamespaceUsageUnitTest extends AbstractSniffUnitTest
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
    protected function getErrorList($testFile = '')
    {
        return array();
    }

    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $testFile test file
     *
     * @return array(int => int)
     */
    protected function getWarningList($testFile = '')
    {
        switch ($testFile) {
        case 'UnnecessaryNamespaceUsageUnitTest.pass.inc':
            return array();
        case 'UnnecessaryNamespaceUsageUnitTest.fail.inc':
            return array(
                17 => 1,
                19 => 1,
                24 => 1,
                25 => 1,
                26 => 2,
                28 => 1,
                30 => 2,
                32 => 1,
                33 => 1,
                40 => 1,
                44 => 1,
                45 => 1,
                46 => 1,
            );
        }

        return null;
    }
}
 