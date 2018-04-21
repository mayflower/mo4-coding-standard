<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/mayflower/mo4-coding-standard
 */
namespace MO4\Sniffs\Arrays;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Multi Line Array sniff.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013-2017 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
class MultiLineArraySniff implements Sniff
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
    public function register()
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
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $current = $tokens[$stackPtr];

        if ($current['code'] === T_ARRAY) {
            $arrayType = 'parenthesis';
            $start     = $current['parenthesis_opener'];
            $end       = $current['parenthesis_closer'];
        } else {
            $arrayType = 'bracket';
            $start     = $current['bracket_opener'];
            $end       = $current['bracket_closer'];
        }

        if ($tokens[$start]['line'] === $tokens[$end]['line']) {
            return;
        }

        if ($tokens[($start + 2)]['line'] === $tokens[$start]['line']) {
            $fixable = $phpcsFile->addFixableError(
                sprintf(
                    'opening %s of multi line array must be followed by newline',
                    $arrayType
                ),
                $start,
                'OpeningMustBeFollowedByNewline'
            );

            if ($fixable === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewline($start);
                $phpcsFile->fixer->endChangeset();
            }
        }

        if ($tokens[($end - 2)]['line'] === $tokens[$end]['line']) {
            $fixable = $phpcsFile->addFixableError(
                sprintf(
                    'closing %s of multi line array must in own line',
                    $arrayType
                ),
                $end,
                'ClosingMustBeInOwnLine'
            );

            if ($fixable === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewlineBefore($end);
                $phpcsFile->fixer->endChangeset();
            }
        }

    }//end process()


}//end class
