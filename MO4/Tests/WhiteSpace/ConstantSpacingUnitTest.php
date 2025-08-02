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

namespace MO4\Tests\WhiteSpace;

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
final class ConstantSpacingUnitTest extends AbstractMo4SniffUnitTest
{
    protected $expectedErrorList = [
        'ConstantSpacingUnitTest.pass.inc' => [],
        'ConstantSpacingUnitTest.fail.inc' => [
            4  => 1,
            5  => 1,
            6  => 1,
            10 => 1,
            12 => 1,
            13 => 1,
            14 => 1,
            15 => 1,
            18 => 1,
            22 => 1,
            23 => 1,
            24 => 1,
        ],
    ];
}
