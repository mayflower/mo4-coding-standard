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

namespace MO4\Tests\Strings;

use MO4\Tests\AbstractMo4SniffUnitTest;

/**
 * Unit test class for the VariableInDoubleQuotedString sniff.
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
final class VariableInDoubleQuotedStringUnitTest extends AbstractMo4SniffUnitTest
{
    protected $expectedErrorList = [
        'VariableInDoubleQuotedStringUnitTest.pass.inc' => [],
        'VariableInDoubleQuotedStringUnitTest.fail.inc' => [
            3  => 1,
            4  => 1,
            5  => 2,
            6  => 2,
            7  => 1,
            8  => 1,
            9  => 1,
            10 => 1,
            11 => 1,
        ],
    ];
}
