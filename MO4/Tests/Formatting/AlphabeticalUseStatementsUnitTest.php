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

namespace MO4\Tests\Formatting;

use MO4\Tests\AbstractMo4SniffUnitTest;

/**
 * Unit test class for the AlphabeticalUseStatements sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2013-2021 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
final class AlphabeticalUseStatementsUnitTest extends AbstractMo4SniffUnitTest
{
    protected $expectedErrorList = [
        'AlphabeticalUseStatementsUnitTest.pass.inc'   => [],
        'AlphabeticalUseStatementsUnitTest.pass.1.inc' => [],
        'AlphabeticalUseStatementsUnitTest.fail.1.inc' => [
            4  => 1,
            5  => 1,
            8  => 1,
            9  => 1,
            12 => 1,
        ],
        // Take care, more than one fix will be applied.
        'AlphabeticalUseStatementsUnitTest.fail.2.inc' => [
            6 => 1,
            8 => 1,
        ],
        'AlphabeticalUseStatementsUnitTest.fail.3.inc' => [
            7  => 1,
            8  => 1,
            10 => 1,
            15 => 1,
        ],
        'AlphabeticalUseStatementsUnitTest.fail.4.inc' => [
            4  => 1,
            8  => 1,
            13 => 1,
            17 => 1,
            20 => 1,
            21 => 1,
        ],
        'AlphabeticalUseStatementsUnitTest.fail.6.inc' => [5 => 1],
    ];
}
