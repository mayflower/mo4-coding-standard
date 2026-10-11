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

namespace MO4\Sniffs\Arrays;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractArraySniff;

/**
 * Multi Line Array sniff.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2013-2017 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 *
 * @psalm-api
 */
class MultiLineArraySniff extends AbstractArraySniff
{
    /**
     * Processes a single-line array definition.
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
     */
    protected function processSingleLineArray(File $phpcsFile, int $stackPtr, int $arrayStart, int $arrayEnd, array $indices): void
    {
        // nothing to do for single lines arrays
    }

    /**
     * Processes a multi-line array definition.
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
     */
    protected function processMultiLineArray(File $phpcsFile, int $stackPtr, int $arrayStart, int $arrayEnd, array $indices): void
    {
        $tokens = $phpcsFile->getTokens();

        $arrayType = T_OPEN_PARENTHESIS === $tokens[$arrayStart]['code'] ? 'parenthesis' : 'bracket';

        $openLine = $tokens[$arrayStart]['line'];

        // Check opening delimiter spacing
        if ($tokens[($arrayStart + 2)]['line'] === $openLine) {
            $fixable = $phpcsFile->addFixableError(
                \sprintf(
                    'opening %s of multi line array must be followed by newline',
                    $arrayType
                ),
                $arrayStart,
                'OpeningMustBeFollowedByNewline'
            );

            if (true === $fixable) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewline($arrayStart);
                $phpcsFile->fixer->endChangeset();
            }
        }

        // Check closing delimiter spacing
        if ($tokens[($arrayEnd - 2)]['line'] !== $tokens[$arrayEnd]['line']) {
            return;
        }

        $fixable = $phpcsFile->addFixableError(
            \sprintf(
                'closing %s of multi line array must in own line',
                $arrayType
            ),
            $arrayEnd,
            'ClosingMustBeInOwnLine'
        );

        if (true !== $fixable) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->addNewlineBefore($arrayEnd);
        $phpcsFile->fixer->endChangeset();
    }
}
