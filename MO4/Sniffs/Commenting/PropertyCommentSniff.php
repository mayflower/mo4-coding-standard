<?php

/**
 * This file is part of the mo4-coding-standard (phpcs standard)
 *
 * @author  Xaver Loppenstedt <xaver@loppenstedt.de>
 * @license http://spdx.org/licenses/MIT MIT License
 * @link    https://github.com/mayflower/mo4-coding-standard
 */
declare(strict_types=1);

namespace MO4\Sniffs\Commenting;

use MO4\Library\PregLibrary;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;

/**
 * Property Comment Sniff sniff.
 *
 * Doc blocks of class properties must be multiline and have exactly one var
 * annotation.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @copyright 2013-2014 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/mayflower/mo4-coding-standard
 */
class PropertyCommentSniff extends AbstractScopeSniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array<string>
     */
    public $supportedTokenizers = ['PHP'];

    /**
     * List of token types this sniff analyzes
     *
     * @var array<int, int>
     */
    private $myTokenTypes = [
        T_VARIABLE,
        T_CONST,
    ];


    /**
     * Construct PropertyCommentSniff
     *
     * @throws RuntimeException
     */
    public function __construct()
    {
        $scopes = [T_CLASS];

        parent::__construct($scopes, $this->myTokenTypes, true);

    }//end __construct()


    /**
     * Processes a token that is found within the scope that this test is
     * listening to.
     *
     * @param File $phpcsFile The file where this token was found.
     * @param int  $stackPtr  The position in the stack where this
     *                        token was found.
     * @param int  $currScope The position in the tokens array that
     *                        opened the scope that this test is
     *                        listening for.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected function processTokenWithinScope(
        File $phpcsFile,
        $stackPtr,
        $currScope
    ): void {
        $find   = [
            T_COMMENT,
            T_DOC_COMMENT_CLOSE_TAG,
            T_CLASS,
            T_CONST,
            T_FUNCTION,
            T_VARIABLE,
            T_OPEN_TAG,
        ];
        $tokens = $phpcsFile->getTokens();

        // Before even checking the doc blocks above the current var/const,
        // check if we have a single line comment after it on the same line,
        // and if that one is OK.
        $postComment = $phpcsFile->findNext(
            [
                T_DOC_COMMENT_OPEN_TAG,
                T_COMMENT,
            ],
            $stackPtr
        );
        if ($postComment !== false
            && $tokens[$postComment]['line'] === $tokens[$stackPtr]['line']
        ) {
            if ($tokens[$postComment]['content'] === '/**') {
                // That's an error already.
                $phpcsFile->addError(
                    'no doc blocks are allowed directly after declaration',
                    $stackPtr,
                    'NoDocBlockAllowed'
                );
            } else if (\strpos($tokens[$postComment]['content'], '//') !== 0
                && \substr($tokens[$postComment]['content'], -2) !== '*/'
            ) {
                $phpcsFile->addError(
                    'no multiline comments after declarations allowed',
                    $stackPtr,
                    'MustBeOneLine'
                );
            }
        }//end if

        // Don't do constants for now.
        if ($tokens[$stackPtr]['code'] === T_CONST) {
            return;
        }

        $commentEnd = (int) $phpcsFile->findPrevious($find, ($stackPtr - 1));

        $conditions    = $tokens[$commentEnd]['conditions'];
        $lastCondition = \array_pop($conditions);
        if ($lastCondition !== T_CLASS) {
            return;
        }

        $code = $tokens[$commentEnd]['code'];

        if ($code === T_DOC_COMMENT_CLOSE_TAG) {
            $commentStart = $tokens[$commentEnd]['comment_opener'];

            // Check if this comment is completely in one line,
            // above the current line,
            // and has a variable preceding it in the same line.
            // If yes, it doesn't count.
            $firstTokenOnLine = $phpcsFile->findFirstOnLine(
                $this->myTokenTypes,
                $commentEnd
            );
            if ($firstTokenOnLine !== false
                && $tokens[$commentStart]['line'] === $tokens[$commentEnd]['line']
                && $tokens[$stackPtr]['line'] > $tokens[$commentEnd]['line']
            ) {
                return;
            }

            $isCommentOneLiner
                = $tokens[$commentStart]['line'] === $tokens[$commentEnd]['line'];

            $length         = ($commentEnd - $commentStart + 1);
            $tokensAsString = $phpcsFile->getTokensAsString(
                $commentStart,
                $length
            );

            $vars = PregLibrary::mo4_preg_split('/\s+@var\s+/', $tokensAsString);

            $varCount = (\count($vars) - 1);
            if (($varCount === 0) || ($varCount > 1)) {
                $phpcsFile->addError(
                    'property doc comment must have exactly one @var annotation',
                    $commentStart,
                    'MustHaveOneVarAnnotationDefined'
                );
            }

            if ($varCount === 1) {
                if ($isCommentOneLiner === true) {
                    $fix = $phpcsFile->addFixableError(
                        'property doc comment must be multi line',
                        $commentEnd,
                        'NotMultiLineDocBlock'
                    );

                    if ($fix === true) {
                        $phpcsFile->fixer->beginChangeset();
                        $phpcsFile->fixer->addContent($commentStart, "\n     *");
                        $phpcsFile->fixer->replaceToken(
                            ($commentEnd - 1),
                            \rtrim($tokens[($commentEnd - 1)]['content'])
                        );
                        $phpcsFile->fixer->addContentBefore($commentEnd, "\n     ");
                        $phpcsFile->fixer->endChangeset();
                    }
                }
            } else if ($isCommentOneLiner === true) {
                $phpcsFile->addError(
                    'property doc comment must be multi line',
                    $commentEnd,
                    'NotMultiLineDocBlock'
                );
            }//end if
        } else if ($code === T_COMMENT) {
            // It seems that when we are in here,
            // then we have a line comment at $commentEnd.
            // Now, check if the same comment has
            // a variable definition on the same line.
            // If yes, it doesn't count.
            $firstOnLine = $phpcsFile->findFirstOnLine(
                $this->myTokenTypes,
                $commentEnd
            );

            if ($firstOnLine === false) {
                $commentStart = $phpcsFile->findPrevious(
                    T_COMMENT,
                    $commentEnd,
                    0,
                    true
                );
                $phpcsFile->addError(
                    'property doc comment must begin with /**',
                    ((int) $commentStart + 1),
                    'NotADocBlock'
                );
            }
        }//end if

    }//end processTokenWithinScope()


    /**
     * Process tokens outside scope.
     *
     * @param File $phpcsFile The file where this token was found.
     * @param int  $stackPtr  The position in the stack where this
     *                        token was found.
     *
     * @return void
     */
    protected function processTokenOutsideScope(File $phpcsFile, $stackPtr): void
    {

    }//end processTokenOutsideScope()


}//end class
