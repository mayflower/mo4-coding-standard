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

use MO4\Tests\AbstractMo4SniffUnitTestCase;

/**
 * Unit test class for the MultipleEmptyLines sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
final class MultipleEmptyLinesUnitTest extends AbstractMo4SniffUnitTestCase
{
    protected $expectedErrorList = [
        'MultipleEmptyLinesUnitTest.pass.inc' => [],
        'MultipleEmptyLinesUnitTest.fail.inc' => [
            2  => 1,
            14 => 1,
            21 => 1,
            24 => 1,
            29 => 1,
        ],
    ];
}
