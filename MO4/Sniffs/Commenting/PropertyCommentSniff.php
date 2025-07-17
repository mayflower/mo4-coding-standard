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
 *
 * @copyright 2013-2014 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 *
 * @psalm-api
 */
class PropertyCommentSniff extends AbstractScopeSniff
{
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
    }

    /**
     * Processes a token that is found within the scope that this test is
     * listening to.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
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
    #[\Override]
    protected function processTokenWithinScope(File $phpcsFile, $stackPtr, $currScope): void
    {
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

        if (false !== $postComment
            && $tokens[$postComment]['line'] === $tokens[$stackPtr]['line']
        ) {
            if ('/**' === $tokens[$postComment]['content']) {
                // That's an error already.
                $phpcsFile->addError(
                    'no doc blocks are allowed directly after declaration',
                    $stackPtr,
                    'NoDocBlockAllowed'
                );
            } elseif (!\str_starts_with($tokens[$postComment]['content'], '//')
                && !\str_ends_with($tokens[$postComment]['content'], '*/')
            ) {
                $phpcsFile->addError(
                    'no multiline comments after declarations allowed',
                    $stackPtr,
                    'MustBeOneLine'
                );
            }
        }

        // Don't do constants for now.
        if (T_CONST === $tokens[$stackPtr]['code']) {
            return;
        }

        $commentEnd = (int) $phpcsFile->findPrevious($find, ($stackPtr - 1));

        $conditions    = $tokens[$commentEnd]['conditions'];
        $lastCondition = \array_pop($conditions);

        if (T_CLASS !== $lastCondition) {
            return;
        }

        $code = $tokens[$commentEnd]['code'];

        if (T_DOC_COMMENT_CLOSE_TAG === $code) {
            $commentStart = $tokens[$commentEnd]['comment_opener'];

            // Check if this comment is completely in one line,
            // above the current line,
            // and has a variable preceding it in the same line.
            // If yes, it doesn't count.
            $firstTokenOnLine = $phpcsFile->findFirstOnLine(
                $this->myTokenTypes,
                $commentEnd
            );

            if (false !== $firstTokenOnLine
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

            $vars = PregLibrary::MO4PregSplit('/\s+@var\s+/', $tokensAsString);

            $varCount = (\count($vars) - 1);

            if ((0 === $varCount) || ($varCount > 1)) {
                $phpcsFile->addError(
                    'property doc comment must have exactly one @var annotation',
                    $commentStart,
                    'MustHaveOneVarAnnotationDefined'
                );
            }

            if (1 === $varCount) {
                if (true === $isCommentOneLiner) {
                    $fix = $phpcsFile->addFixableError(
                        'property doc comment must be multi line',
                        $commentEnd,
                        'NotMultiLineDocBlock'
                    );

                    if (true === $fix) {
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
            } elseif (true === $isCommentOneLiner) {
                $phpcsFile->addError(
                    'property doc comment must be multi line',
                    $commentEnd,
                    'NotMultiLineDocBlock'
                );
            }
        // phpcs:disable SlevomatCodingStandard.ControlStructures.EarlyExit
        } elseif (T_COMMENT === $code) {
            // It seems that when we are in here,
            // then we have a line comment at $commentEnd.
            // Now, check if the same comment has
            // a variable definition on the same line.
            // If yes, it doesn't count.
            $firstOnLine = $phpcsFile->findFirstOnLine(
                $this->myTokenTypes,
                $commentEnd
            );

            // phpcs:enable SlevomatCodingStandard.ControlStructures.EarlyExit
            if (false === $firstOnLine) {
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
        }
    }

    /**
     * Process tokens outside scope.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param File $phpcsFile The file where this token was found.
     * @param int  $stackPtr  The position in the stack where this
     *                        token was found.
     *
     * @return void
     */
    #[\Override]
    protected function processTokenOutsideScope(File $phpcsFile, $stackPtr): void
    {
    }
}
