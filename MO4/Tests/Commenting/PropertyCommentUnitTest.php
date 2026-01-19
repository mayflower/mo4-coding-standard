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

namespace MO4\Tests\Commenting;

use MO4\Tests\AbstractMo4SniffUnitTestCase;

/**
 * Unit test class for the AlphabeticalUseStatements sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2014-2021 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
final class PropertyCommentUnitTest extends AbstractMo4SniffUnitTestCase
{
    protected $expectedErrorList = [
        'PropertyCommentUnitTest.pass.inc' => [],
        'PropertyCommentUnitTest.fail.inc' => [
            7  => 1,
            10 => 1,
            17 => 1,
            26 => 2,
            29 => 1,
            34 => 1,
            37 => 2,
            41 => 1,
            44 => 1,
        ],
    ];
}
