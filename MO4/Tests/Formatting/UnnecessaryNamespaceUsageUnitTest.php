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

use MO4\Tests\AbstractMo4SniffUnitTestCase;

/**
 * Unit test class for the UnnecessaryNamespaceUsageUnitTest sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @author    Marco Jantke <marco.jantke@gmail.com>
 * @author    Steffen Ritter <steffenritter1@gmail.com>
 *
 * @copyright 2013-21 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
final class UnnecessaryNamespaceUsageUnitTest extends AbstractMo4SniffUnitTestCase
{
    protected $expectedWarningList = [
        'UnnecessaryNamespaceUsageUnitTest.pass.1.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.2.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.3.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.4.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.5.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.pass.6.inc' => [],
        'UnnecessaryNamespaceUsageUnitTest.fail.1.inc' => [
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
            52 => 1,
            56 => 1,
        ],
        'UnnecessaryNamespaceUsageUnitTest.fail.2.inc' => [
            10 => 1,
            11 => 1,
        ],
        'UnnecessaryNamespaceUsageUnitTest.fail.3.inc' => [
            15 => 1,
            16 => 1,
            17 => 1,
            18 => 1,
            22 => 1,
            23 => 1,
            25 => 3,
        ],
        'UnnecessaryNamespaceUsageUnitTest.fail.4.inc' => [],
    ];
}
