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
 * Alphabetical Use Statements sniff.
 *
 * Use statements must be in alphabetical order, grouped by empty lines
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013-2014 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */
class MO4_Sniffs_Commenting_PropertyCommentSniff
    extends PHP_CodeSniffer_Standards_AbstractScopeSniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP');

    /**
     * Construct PropertyCommentSniff
     */
    function __construct()
    {
        $scopes = array(
            T_CLASS,
        );

        $listen = array(
            T_VARIABLE,
        );

        parent::__construct($scopes, $listen, true);
    }

    /**
     * Processes a token that is found within the scope that this test is
     * listening to.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where this token was found.
     * @param int                  $stackPtr  The position in the stack where this
     *                                        token was found.
     * @param int                  $currScope The position in the tokens array that
     *                                        opened the scope that this test is
     *                                        listening for.
     *
     * @return void
     */
    protected function processTokenWithinScope(
        PHP_CodeSniffer_File $phpcsFile,
        $stackPtr,
        $currScope
    ) {
        $find   = array(
            T_COMMENT,
            T_DOC_COMMENT,
            T_CLASS,
            T_FUNCTION,
            T_VARIABLE,
            T_OPEN_TAG,
        );
        $tokens = $phpcsFile->getTokens();

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        if ($commentEnd === false) {
            return;
        }


        $conditions = $tokens[$commentEnd]['conditions'];
        $lastCondition = array_pop($conditions);
        if ($lastCondition !== T_CLASS) {
            return;
        }

        $code = $tokens[$commentEnd]['code'];
        if ($code === T_DOC_COMMENT) {
            $commentStart = $phpcsFile->findPrevious(
                T_DOC_COMMENT,
                $commentEnd - 1,
                null,
                true
            ) + 1;

            $content = $tokens[$commentEnd]['content'];
            if ($commentStart === $commentEnd) {
                $phpcsFile->addError(
                    'property doc comment must be multi line',
                    $commentEnd,
                    'NotMultiLineDocBlock'
                );

                return;
            }

            $secondLast = $commentEnd - 1;
            $content    = $tokens[$secondLast]['content'];
            if (strstr($content, ' @var ') === false) {
                $phpcsFile->addError(
                    'property doc comment must have one @var on last line',
                    $secondLast,
                    'NoVarDefined'
                );

                return;
            }

            $length         = $secondLast - $commentStart - 1;
            $tokensAsString = $phpcsFile->getTokensAsString(
                $commentStart,
                $length
            );
            if (strstr($tokensAsString, '@var') !== false) {
                $phpcsFile->addError(
                    'property doc comment must have only one @var on last line',
                    $commentStart,
                    'NoVarDefined'
                );

                return;
            }
        }
    }
}
 