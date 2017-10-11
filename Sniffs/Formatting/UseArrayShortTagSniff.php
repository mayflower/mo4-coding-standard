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

namespace MO4\Sniffs\Formatting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Use Array Short Tag sniff.
 *
 * Use the array short tag [...] instead of array(...)
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */
class UseArrayShortTagSniff implements Sniff
{


    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array(int)
     * @see    Tokens.php
     */
    public function register()
    {
        return array(T_ARRAY);

    }//end register()


    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @param File $phpcsFile The PHP_CodeSniffer file where the
     *                        token was found.
     * @param int  $stackPtr  The position in the PHP_CodeSniffer
     *                        file's token stack where the token
     *                        was found.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $fix = $phpcsFile->addFixableError('Array short tag [ ... ] must be used', $stackPtr, 'NoShortTagUsed');

        if ($fix === true) {
            $tokens = $phpcsFile->getTokens();
            $token  = $tokens[$stackPtr];

            $phpcsFile->fixer->beginChangeset();
            $phpcsFile->fixer->replaceToken($stackPtr, '');
            $phpcsFile->fixer->replaceToken($token['parenthesis_opener'], '[');
            for ($i = ($stackPtr + 1); $i < $token['parenthesis_opener']; $i++) {
                $phpcsFile->fixer->replaceToken($i, '');
            }

            $phpcsFile->fixer->replaceToken($token['parenthesis_closer'], ']');
            $phpcsFile->fixer->endChangeset();
        }

    }//end process()


}//end class
