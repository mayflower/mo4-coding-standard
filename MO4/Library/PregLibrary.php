<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Michael Moll <mmoll@mmoll.at>
 *
 * @license http://spdx.org/licenses/MIT MIT License
 *
 * @link    https://github.com/mayflower/mo4-coding-standard
 */

declare(strict_types=1);

namespace MO4\Library;

use PHP_CodeSniffer\Exceptions\RuntimeException;

final class PregLibrary
{
    /**
     * Split string by a regular expression
     *
     * @param string $pattern The pattern to search for, as a string.
     * @param string $subject The input string.
     * @param int    $limit   If specified, then only substrings up to limit are returned with the rest of the string
     *                        being placed in the last substring. A limit of -1, 0 or NULL means "no limit" and, as is
     *                        standard across PHP, you can use NULL to skip to the flags parameter.
     * @param int    $flags   Can be any combination of the following flags (combined with the | bitwise operator):
     *                        PREG_SPLIT_NO_EMPTY:       If this flag is set, only non-empty pieces will be returned.
     *                        PREG_SPLIT_DELIM_CAPTURE:  If this flag is set, parenthesized expression in the delimiter
     *                        pattern will be captured and returned as well.
     *                        PREG_SPLIT_OFFSET_CAPTURE: If this flag is set, for every occurring match the appendant
     *                        string offset will also be returned.
     *
     * @return array<string>|array<array>
     *
     * @throws RuntimeException
     *
     * @psalm-suppress ArgumentTypeCoercion
     */
    public static function MO4PregSplit(string $pattern, string $subject, int $limit = -1, int $flags = 0): array
    {
        $pregSplitResult = \preg_split($pattern, $subject, $limit, $flags);

        // @phan-suppress-next-line PhanTypeComparisonToArray
        if (false === $pregSplitResult) {
            throw new RuntimeException('Unexpected Error in MO4 Coding Standard.');
        }

        return $pregSplitResult;
    }
}
