<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license http://spdx.org/licenses/MIT MIT License
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
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
class ArrayDoubleArrowAlignmentSniff implements Sniff
{

    /**
     * Define all types of arrays.
     *
     * @var array
     */
    protected  $arrayTokens = [
        T_ARRAY,
        T_OPEN_SHORT_ARRAY,
    ];


    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array<int, int>
     * @see    Tokens.php
     */
    public function register(): array
    {
        return $this->arrayTokens;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in
     *                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        $tokens  = $phpcsFile->getTokens();
        $current = $tokens[$stackPtr];

        if ($current['code'] === T_ARRAY) {
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
            if (\in_array($current['code'], $this->arrayTokens, true) === true) {
                if ($current['code'] === T_ARRAY) {
                    $i = ($current['parenthesis_closer'] + 1);
                } else {
                    $i = ($current['bracket_closer'] + 1);
                }

                continue;
            }

            // Skip closures in array.
            if ($current['code'] === T_CLOSURE) {
                $i = ($current['scope_closer'] + 1);
                continue;
            }

            if ($current['code'] === T_DOUBLE_ARROW) {
                $assignments[] = $i;
                $column        = $previous['column'];
                $line          = $current['line'];

                if ($lastLine === $line) {
                    $previousComma = $this->getPreviousComma($phpcsFile, $i, $start);

                    $msg = 'only one "=>" assignments per line is allowed in a multi line array';

                    if ($previousComma !== false) {
                        $fixable = $phpcsFile->addFixableError($msg, $i, 'OneAssignmentPerLine');

                        if ($fixable === true) {
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

                $j = ((int) $i - 1);
                while (($j >= 0) && ($tokens[$j]['line'] === $current['line'])) {
                    if (\in_array($tokens[$j]['code'], PHP_CodeSniffer_Tokens::$emptyTokens, true) === false) {
                        $hasKeyInLine = true;
                    }

                    $j--;
                }

                if ($hasKeyInLine === false) {
                    $fixable = $phpcsFile->addFixableError(
                        'in arrays, keys and "=>" must be on the same line',
                        $i,
                        'KeyAndValueNotOnSameLine'
                    );

                    if ($fixable === true) {
                        $phpcsFile->fixer->beginChangeset();
                        $phpcsFile->fixer->replaceToken($j, '');
                        $phpcsFile->fixer->endChangeset();
                    }
                }

                if ($column > $keyEndColumn) {
                    $keyEndColumn = $column;
                }

                $lastLine = $line;
            }//end if
        }//end for

        $doubleArrowStartColumn = ($keyEndColumn + 1);

        foreach ($assignments as $ptr) {
            $current = $tokens[$ptr];
            $column  = $current['column'];

            $beforeArrowPtr = ($ptr - 1);
            $currentIndent  = \strlen($tokens[$beforeArrowPtr]['content']);
            $correctIndent  = (int) ($currentIndent - $column + $doubleArrowStartColumn);
            if ($column !== $doubleArrowStartColumn) {
                $fixable = $phpcsFile->addFixableError("each \"=>\" assignments must be aligned; current indentation before \"=>\" are $currentIndent space(s), must be $correctIndent space(s)", $ptr, 'AssignmentsNotAligned');

                if ($fixable === false) {
                    continue;
                }

                $phpcsFile->fixer->beginChangeset();
                if ($tokens[$beforeArrowPtr]['code'] === T_WHITESPACE) {
                    $phpcsFile->fixer->replaceToken($beforeArrowPtr, \str_repeat(' ', $correctIndent));
                } else {
                    $phpcsFile->fixer->addContent($beforeArrowPtr, \str_repeat(' ', $correctIndent));
                }

                $phpcsFile->fixer->endChangeset();
            }
        }//end foreach

    }//end process()


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
    private function getPreviousComma(File $phpcsFile, $stackPtr, $start)
    {
        $previousComma = false;
        $tokens        = $phpcsFile->getTokens();

        $ptr = $phpcsFile->findPrevious([T_COMMA, T_CLOSE_SHORT_ARRAY], $stackPtr, $start);
        while ($ptr !== false) {
            if ($tokens[$ptr]['code'] === T_COMMA) {
                $previousComma = $ptr;
                break;
            }

            if ($tokens[$ptr]['code'] === T_CLOSE_SHORT_ARRAY) {
                $ptr = $tokens[$ptr]['bracket_opener'];
            }

            $ptr = $phpcsFile->findPrevious([T_COMMA, T_CLOSE_SHORT_ARRAY], ($ptr - 1), $start);
        }

        return $previousComma;

    }//end getPreviousComma()


}//end class
