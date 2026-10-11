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

namespace MO4\Sniffs\Arrays;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractArraySniff;
use PHP_CodeSniffer\Util\Tokens as PHP_CodeSniffer_Tokens;

/**
 * Array Double Arrow Alignment sniff.
 *
 * '=>' must be aligned in arrays, and the key and the '=>' must be in the same line
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 *
 * @psalm-api
 */
class ArrayDoubleArrowAlignmentSniff extends AbstractArraySniff
{
    /**
     * Processes a single-line array definition.
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
     */
    protected function processSingleLineArray(File $phpcsFile, int $stackPtr, int $arrayStart, int $arrayEnd, array $indices): void
    {
        // nothing to do for single lines arrays
    }

    /**
     * Processes a multi-line array definition.
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
     */
    protected function processMultiLineArray(File $phpcsFile, int $stackPtr, int $arrayStart, int $arrayEnd, array $indices): void
    {
        $tokens = $phpcsFile->getTokens();

        $assignments      = [];
        $keyEndColumn     = -1;
        $lastLine         = -1;
        $assignmentsCount = 0;
        $previousComma    = false;

        for ($i = ($arrayStart + 1); $i < $arrayEnd; $i++) {
            $current = $tokens[$i];

            // Skip nested arrays.
            if (T_OPEN_SHORT_ARRAY === $current['code'] || T_ARRAY === $current['code']) {
                $i = T_ARRAY === $current['code'] ? ($current['parenthesis_closer'] + 1) : ($current['bracket_closer'] + 1);

                continue;
            }

            // Skip closures in array.
            if (T_CLOSURE === $current['code']) {
                $i = ($current['scope_closer'] + 1);

                continue;
            }

            // Track commas
            if (T_COMMA === $current['code']) {
                $previousComma = $i;

                continue;
            }

            // Only process double arrow assignments
            if (T_DOUBLE_ARROW !== $current['code']) {
                continue;
            }

            // Record assignment position
            $previous                       = $tokens[($i - 1)];
            $assignments[$assignmentsCount] = $i;
            $assignmentsCount++;

            // Get column info once
            $column = $previous['column'];
            $line   = $current['line'];

            // Check for multiple assignments per line
            if ($lastLine === $line) {
                $msg = 'only one "=>" assignments per line is allowed in a multi line array';

                if (false !== $previousComma) {
                    $fixable = $phpcsFile->addFixableError($msg, $i, 'OneAssignmentPerLine');

                    if (true === $fixable) {
                        $phpcsFile->fixer->beginChangeset();
                        $phpcsFile->fixer->addNewline((int) $previousComma);
                        $phpcsFile->fixer->endChangeset();
                    }
                } else {
                    // Remove current and previous '=>' from array for further processing.
                    unset($assignments[$assignmentsCount - 1], $assignments[$assignmentsCount - 2]);
                    $assignmentsCount -= 2;
                    $phpcsFile->addError($msg, $i, 'OneAssignmentPerLine');
                }
            }

            // Check if key is on the same line - optimized lookup
            $hasKeyInLine = false;
            $j            = ($i - 1);

            // Stop at the beginning of the line for efficiency
            $lineStart = $current['line'];

            while (($j >= 0) && ($tokens[$j]['line'] === $lineStart)) {
                if (!isset(PHP_CodeSniffer_Tokens::EMPTY_TOKENS[$tokens[$j]['code']])) {
                    $hasKeyInLine = true;

                    break;
                }

                $j--;
            }

            if (false === $hasKeyInLine) {
                $fixable = $phpcsFile->addFixableError(
                    'in arrays, keys and "=>" must be on the same line',
                    $i,
                    'KeyAndValueNotOnSameLine'
                );

                if (true === $fixable) {
                    $phpcsFile->fixer->beginChangeset();
                    $phpcsFile->fixer->replaceToken($j, '');
                    $phpcsFile->fixer->endChangeset();
                }
            }

            // Track max column position
            if ($column > $keyEndColumn) {
                $keyEndColumn = $column;
            }

            $lastLine = $line;
        }

        // Calculate alignment offset
        $doubleArrowStartColumn = ($keyEndColumn + 1);

        // Process assignments with minimal overhead
        foreach ($assignments as $ptr) {
            $current = $tokens[$ptr];
            $column  = $current['column'];

            if ($column === $doubleArrowStartColumn) {
                continue;
            }

            $beforeArrowPtr = ($ptr - 1);
            $currentIndent  = \strlen($tokens[$beforeArrowPtr]['content']);
            $correctIndent  = ($currentIndent - $column + $doubleArrowStartColumn);

            $fixable = $phpcsFile->addFixableError("each \"=>\" assignments must be aligned; current indentation before \"=>\" are {$currentIndent} space(s), must be {$correctIndent} space(s)", $ptr, 'AssignmentsNotAligned');

            if (false === $fixable) {
                continue;
            }

            $phpcsFile->fixer->beginChangeset();

            if (T_WHITESPACE === $tokens[$beforeArrowPtr]['code']) {
                $phpcsFile->fixer->replaceToken($beforeArrowPtr, \str_repeat(' ', $correctIndent));
            } else {
                $phpcsFile->fixer->addContent($beforeArrowPtr, \str_repeat(' ', $correctIndent));
            }

            $phpcsFile->fixer->endChangeset();
        }
    }
}
