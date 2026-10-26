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

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;

/**
 * Property Comment Sniff.
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
    private array $myTokenTypes = [
        T_VARIABLE,
        T_CONST,
    ];

    /**
     * Token types searched when walking backwards to a preceding comment.
     */
    private const PRE_COMMENT = [
        T_COMMENT,
        T_DOC_COMMENT_CLOSE_TAG,
        T_CLASS,
        T_CONST,
        T_FUNCTION,
        T_VARIABLE,
        T_OPEN_TAG,
    ];

    /**
     * Token types searched when looking for a comment following a declaration.
     */
    private const POST_COMMENT = [
        T_DOC_COMMENT_OPEN_TAG,
        T_COMMENT,
    ];

    /**
     * Constructor for PropertyCommentSniff.
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
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param File $phpcsFile The file where this token was found.
     * @param int  $stackPtr  The position in the stack where this
     *                        token was found.
     * @param int  $currScope The position in the tokens array that
     *                        opened the scope that this test is
     *                        listening for.
     *
     *
     * @throws RuntimeException
     */
    protected function processTokenWithinScope(File $phpcsFile, int $stackPtr, int $currScope): void
    {
        $tokens = $phpcsFile->getTokens();

        // Don't do constants for now, so return early
        if (T_CONST === $tokens[$stackPtr]['code']) {
            return;
        }

        // Before even checking the doc blocks above the current var/const,
        // check if we have a single line comment after it on the same line,
        // and if that one is OK.
        $postComment = $phpcsFile->findNext(
            self::POST_COMMENT,
            $stackPtr
        );

        if (false !== $postComment
            && $tokens[$postComment]['line'] === $tokens[$stackPtr]['line']
        ) {
            // Check for doc blocks after declarations
            if ('/**' === $tokens[$postComment]['content']) {
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

        // Find the comment ending to check if it's a docblock
        $commentEnd = (int) $phpcsFile->findPrevious(self::PRE_COMMENT, $stackPtr - 1);

        // Early return if we don't have a valid comment context
        if (0 === $commentEnd) {
            return;
        }

        $conditions    = $tokens[$commentEnd]['conditions'];
        $lastCondition = \end($conditions);

        // Early return if not within a class scope
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
            if ($tokens[$commentStart]['line'] === $tokens[$commentEnd]['line']
                && $tokens[$stackPtr]['line'] > $tokens[$commentEnd]['line']
            ) {
                $firstTokenOnLine = $phpcsFile->findFirstOnLine(
                    $this->myTokenTypes,
                    $commentEnd
                );

                if (false !== $firstTokenOnLine) {
                    return;
                }
            }

            $isCommentOneLiner
                = $tokens[$commentStart]['line'] === $tokens[$commentEnd]['line'];

            $length         = ($commentEnd - $commentStart + 1);
            $tokensAsString = $phpcsFile->getTokensAsString(
                $commentStart,
                $length
            );

            $varCount = \preg_match_all('/\s+@var\s+/', $tokensAsString);

            // Validate @var annotation count
            if ((0 === $varCount) || ($varCount > 1)) {
                $phpcsFile->addError(
                    'property doc comment must have exactly one @var annotation',
                    $commentStart,
                    'MustHaveOneVarAnnotationDefined'
                );
            }

            // Handle multiline docblock requirement
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
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param File $phpcsFile The file where this token was found.
     * @param int  $stackPtr  The position in the stack where this
     *                        token was found.
     *
     */
    protected function processTokenOutsideScope(File $phpcsFile, int $stackPtr): void
    {
    }
}
