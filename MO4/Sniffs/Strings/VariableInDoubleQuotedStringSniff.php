<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/mayflower/mo4-coding-standard
 */

namespace MO4\Sniffs\Strings;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Variable in Double Quoted String sniff.
 *
 * Variables in double quoted strings must be surrounded by { }
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
class VariableInDoubleQuotedStringSniff implements Sniff
{
    /**
     * The PHP_CodeSniffer object controlling this run.
     *
     * @var File
     */
    private $phpCsFile;


    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array(int)
     *
     * @see Tokens.php
     */
    public function register()
    {
        return [T_DOUBLE_QUOTED_STRING];

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
        $this->phpCsFile = $phpcsFile;

        $varRegExp = '/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

        $tokens  = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];

        $matches = [];

        preg_match_all($varRegExp, $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches as $match) {
            foreach ($match as list($var, $pos)) {
                if ($pos === 1 || $content[($pos - 1)] !== '{') {
                    if (strpos(substr($content, 0, $pos), '{') > 0
                        && strpos(substr($content, 0, $pos), '}') === false
                    ) {
                        continue;
                    }

                    $lastOpeningBrace = strrpos(substr($content, 0, $pos), '{');
                    if ($lastOpeningBrace !== false
                        && $content[($lastOpeningBrace + 1)] === '$'
                    ) {
                        $lastClosingBrace = strrpos(substr($content, 0, $pos), '}');

                        if ($lastClosingBrace !== false
                            && $lastClosingBrace < $lastOpeningBrace
                        ) {
                            continue;
                        }
                    }

                    $fix = $this->phpCsFile->addFixableError(
                        sprintf(
                            'must surround variable %s with { }',
                            $var
                        ),
                        $stackPtr,
                        'NotSurroundedWithBraces'
                    );

                    if ($fix === true) {
                        $correctVariable = $this->surroundVariableWithBraces(
                            $content,
                            $pos,
                            $var
                        );
                        $this->fixPhpCsFile($stackPtr, $correctVariable);
                    }
                }//end if
            }//end foreach
        }//end foreach

    }//end process()


    /**
     * Surrounds a variable with curly brackets
     *
     * @param string $content content
     * @param int    $pos     position
     * @param string $var     variable
     *
     * @return string
     */
    private function surroundVariableWithBraces($content, $pos, $var)
    {
        $before = substr($content, 0, $pos);
        $after  = substr($content, ($pos + strlen($var)));

        return $before.'{'.$var.'}'.$after;

    }//end surroundVariableWithBraces()


    /**
     * Fixes the file
     *
     * @param int    $stackPtr        stack pointer
     * @param string $correctVariable correct variable
     *
     * @return void
     */
    private function fixPhpCsFile($stackPtr, $correctVariable)
    {
        $phpCsFile = $this->phpCsFile;

        $phpCsFile->fixer->beginChangeset();
        $phpCsFile->fixer->replaceToken($stackPtr, $correctVariable);
        $phpCsFile->fixer->endChangeset();

    }//end fixPhpCsFile()


}//end class
