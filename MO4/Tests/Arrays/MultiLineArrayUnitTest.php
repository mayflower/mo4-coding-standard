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

use MO4\Tests\AbstractMo4SniffUnitTestCase;

/**
 * Unit test class for @see MultiLineArraySniff
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
final class MultiLineArrayUnitTest extends AbstractMo4SniffUnitTestCase
{
    protected $expectedErrorList = [
        'MultiLineArrayUnitTest.pass.inc' => [],
        'MultiLineArrayUnitTest.fail.inc' => [
            4  => 1,
            12 => 1,
            18 => 2,
            22 => 1,
            24 => 1,
            28 => 1,
            32 => 1,
        ],
    ];
}
