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
use PHP_CodeSniffer\Sniffs\Sniff;
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
class ArrayDoubleArrowAlignmentSniff implements Sniff
{
    /**
     * Define all types of arrays.
     *
     * @var array
     */
    protected $arrayTokens = [
        // @phan-suppress-next-line PhanUndeclaredConstant
        T_OPEN_SHORT_ARRAY,
        T_ARRAY,
    ];

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array<int, int>
     *
     * @see    Tokens.php
     */
    #[\Override]
    public function register(): array
    {
        return $this->arrayTokens;
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in
     *                        the stack passed in $tokens.
     *
     * @return void
     */
    #[\Override]
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens  = $phpcsFile->getTokens();
        $current = $tokens[$stackPtr];

        if (T_ARRAY === $current['code']) {
            $start = $current['parenthesis_opener'];
            $end   = $current['parenthesis_closer'];
        } else {
            $start = $current['bracket_opener'];
            $end   = $current['bracket_closer'];
        }

        if ($tokens[$start]['line'] === $tokens[$end]['line']) {
            return;
        }

        // phpcs:disable
        /** @var array<int> $assignments */
        $assignments  = [];
        // phpcs:enable
        $keyEndColumn = -1;
        $lastLine     = -1;

        for ($i = ($start + 1); $i < $end; $i++) {
            $current  = $tokens[$i];
            $previous = $tokens[($i - 1)];

            // Skip nested arrays.
            if (\in_array($current['code'], $this->arrayTokens, true)) {
                $i = T_ARRAY === $current['code'] ? ($current['parenthesis_closer'] + 1) : ($current['bracket_closer'] + 1);

                continue;
            }

            // Skip closures in array.
            if (T_CLOSURE === $current['code']) {
                $i = ($current['scope_closer'] + 1);

                continue;
            }

            $i = (int) $i;

            if (T_DOUBLE_ARROW !== $current['code']) {
                continue;
            }

            $assignments[] = $i;
            $column        = $previous['column'];
            $line          = $current['line'];

            if ($lastLine === $line) {
                $previousComma = $this->getPreviousComma($phpcsFile, $i, $start);

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
                    \array_pop($assignments);
                    \array_pop($assignments);
                    $phpcsFile->addError($msg, $i, 'OneAssignmentPerLine');
                }
            }

            $hasKeyInLine = false;

            $j = ($i - 1);

            while (($j >= 0) && ($tokens[$j]['line'] === $current['line'])) {
                if (!\in_array($tokens[$j]['code'], PHP_CodeSniffer_Tokens::$emptyTokens, true)) {
                    $hasKeyInLine = true;
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

            if ($column > $keyEndColumn) {
                $keyEndColumn = $column;
            }

            $lastLine = $line;
        }

        $doubleArrowStartColumn = ($keyEndColumn + 1);

        foreach ($assignments as $ptr) {
            $current = $tokens[$ptr];
            $column  = $current['column'];

            $beforeArrowPtr = ($ptr - 1);
            $currentIndent  = \strlen($tokens[$beforeArrowPtr]['content']);
            $correctIndent  = ($currentIndent - $column + $doubleArrowStartColumn);

            if ($column === $doubleArrowStartColumn) {
                continue;
            }

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

    /**
     * Find previous comma in array.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in
     *                        the stack passed in $tokens.
     * @param int  $start     Start of the array
     *
     * @return bool|int
     */
    private function getPreviousComma(File $phpcsFile, int $stackPtr, int $start)
    {
        $previousComma = false;
        $tokens        = $phpcsFile->getTokens();

        $ptr = $phpcsFile->findPrevious([T_COMMA, T_CLOSE_SHORT_ARRAY], $stackPtr, $start);

        while (false !== $ptr) {
            if (T_COMMA === $tokens[$ptr]['code']) {
                $previousComma = $ptr;

                break;
            }

            if (T_CLOSE_SHORT_ARRAY === $tokens[$ptr]['code']) {
                $ptr = $tokens[$ptr]['bracket_opener'];
            }

            $ptr = $phpcsFile->findPrevious([T_COMMA, T_CLOSE_SHORT_ARRAY], ($ptr - 1), $start);
        }

        return $previousComma;
    }
}
