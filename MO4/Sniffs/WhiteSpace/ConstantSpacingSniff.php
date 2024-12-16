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

namespace MO4\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Multi Line Array sniff.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2013-2021 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 *
 * @psalm-api
 */
class ConstantSpacingSniff implements Sniff
{
    /**
     * Define all types of arrays.
     *
     * @var array
     */
    protected $arrayTokens = [
        T_CONST,
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
        $nextPtr = $stackPtr + 1;
        $next    = $tokens[$nextPtr]['content'];

        if (T_WHITESPACE !== $tokens[$nextPtr]['code']) {
            return;
        }

        if (' ' === $next) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'Keyword const must be followed by a single space, but found %s',
            $stackPtr,
            'Incorrect',
            [\strlen($next)]
        );

        if (true !== $fix) {
            return;
        }

        $phpcsFile->fixer->replaceToken($nextPtr, ' ');
    }
}
