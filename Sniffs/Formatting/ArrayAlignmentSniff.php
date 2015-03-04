<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer-MO4
 * @author   Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license  http://spdx.org/licenses/MIT MIT License
 * @version  GIT: master
 * @link     https://github.com/Mayflower/mo4-coding-standard
 */

/**
 * Array Alignment sniff.
 *
 * '=>' must be aligned in arrays, and the key and the '=>' must be in the same line
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */
class MO4_Sniffs_Formatting_ArrayAlignmentSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Define all types of arrays.
     *
     * @var array
     */
    protected  $arrayTokens = array(
                               T_ARRAY,
                               T_OPEN_SHORT_ARRAY,
                              );


    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array(int)
     * @see    Tokens.php
     */
    public function register()
    {
        return $this->arrayTokens;

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
        } if ($tokens[($end - 2)]['line'] === $tokens[$end]['line']) {
            if ($current['code'] === T_ARRAY) {
                $arrayBrackets = 'parenthesis';
            } else {
                $arrayBrackets = 'bracket';
            }

            $phpcsFile->addError(
                sprintf(
                    'closing %s of array must in own line',
                    $arrayBrackets
                ),
                $end
            );
        }

        $assignments  = array();
        $keyEndColumn = -1;
        $lastLine     = -1;

        for ($i = ($start + 1); $i < $end; $i++) {
            $current  = $tokens[$i];
            $previous = $tokens[($i - 1)];

            // Skip nested arrays.
            if ((in_array($current['code'], $this->arrayTokens)) === true) {
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
                    $msg = 'only one "=>" assignments per line is allowed in a multi line array';
                    $phpcsFile->addError($msg, $i);
                }

                $hasKeyInLine = false;

                $j = ($i - 1);
                while (($j >= 0) && ($tokens[$j]['line'] === $current['line'])) {
                    if ((in_array($tokens[$j]['code'], PHP_CodeSniffer_Tokens::$emptyTokens)) === true) {
                        $hasKeyInLine = true;
                    }

                    $j--;
                }

                if ($hasKeyInLine === false) {
                    $phpcsFile->addError(
                        'in arrays, keys and "=>" must be on the same line',
                        $i
                    );
                }

                if ($column > $keyEndColumn) {
                    $keyEndColumn = $column;
                } else {
                    $keyEndColumn = $keyEndColumn;
                }

                $lastLine = $line;
            }//end if
        }//end for

        foreach ($assignments as $ptr) {
            $current = $tokens[$ptr];
            $column  = $current['column'];

            if ($column !== ($keyEndColumn + 1)) {
                $phpcsFile->addError('each "=>" assignments must be aligned', $ptr);
            }
        }

    }//end process()


}//end class
