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

namespace MO4\Sniffs\Strings;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Variable in Double Quoted String sniff.
 *
 * Variables in double quoted strings must be surrounded by { }
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 *
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 *
 * @psalm-api
 */
class VariableInDoubleQuotedStringSniff implements Sniff
{
    /**
     * Optimized regular expression for checking braces around variables.
     * This avoids multiple scans by combining the check into one pass.
     */
    private const BRACE_CHECK_REGEXP = '/(?<!\{)\$(?:[a-zA-Z_\x7f-\xff][\w\x7f-\xff]*(?:->[a-zA-Z_\x7f-\xff][\w\x7f-\xff]*|\[[^\]]*\])?)(?!\})/';

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array<int, string>
     *
     * @see Tokens.php
     */
    public function register(): array
    {
        return [T_DOUBLE_QUOTED_STRING];
    }

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
     */
    public function process(File $phpcsFile, int $stackPtr): void
    {
        $tokens  = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];

        // Early return if content doesn't contain variables
        if (!\str_contains($content, '$')) {
            return;
        }

        // Use optimized regex that checks for missing braces in one pass
        \preg_match_all(self::BRACE_CHECK_REGEXP, $content, $matches, PREG_OFFSET_CAPTURE);

        // If no variables found without braces, we're done
        if (0 === \count($matches[0])) {
            return;
        }

        $toWrap            = [];
        $scanOffset        = 0;
        $firstOpeningBrace = null;
        $lastOpeningBrace  = null;
        $lastClosingBrace  = null;

        // Process each match to check if it's already properly enclosed
        foreach ($matches[0] as [$var, $pos]) {
            // Skip if it's already in braces
            if (isset($content[$pos - 1]) && '{' === $content[$pos - 1]) {
                continue;
            }

            // Check for opening braces in the scan range
            for ($i = $scanOffset; $i < $pos; $i++) {
                if ('{' === $content[$i]) {
                    $firstOpeningBrace ??= $i;
                    $lastOpeningBrace    = $i;
                } elseif ('}' === $content[$i]) {
                    $lastClosingBrace = $i;
                }
            }

            $scanOffset = $pos;

            // Skip if already properly enclosed
            if (null !== $firstOpeningBrace && $firstOpeningBrace > 0 && null === $lastClosingBrace) {
                continue;
            }

            if (null !== $lastOpeningBrace && '$' === $content[($lastOpeningBrace + 1)] && (null === $lastClosingBrace || $lastClosingBrace < $lastOpeningBrace)) {
                continue;
            }

            $fix = $phpcsFile->addFixableError(
                \sprintf(
                    'must surround variable %s with { }',
                    $var
                ),
                $stackPtr,
                'NotSurroundedWithBraces'
            );

            if (true !== $fix) {
                continue;
            }

            $toWrap[] = [$pos, $var];
        }

        if ([] === $toWrap) {
            return;
        }

        $correctVariable = $content;

        foreach (\array_reverse($toWrap) as [$pos, $var]) {
            $correctVariable = $this->surroundVariableWithBraces(
                $correctVariable,
                $pos,
                $var
            );
        }

        $this->fixPhpCsFile($stackPtr, $correctVariable, $phpcsFile);
    }

    /**
     * Surrounds a variable with curly brackets
     *
     * @param string $content content
     * @param int    $pos     position
     * @param string $var     variable
     *
     */
    private function surroundVariableWithBraces(string $content, int $pos, string $var): string
    {
        $before = \substr($content, 0, $pos);
        $after  = \substr($content, ($pos + \strlen($var)));

        return $before.'{'.$var.'}'.$after;
    }

    /**
     * Fixes the file
     *
     * @param int    $stackPtr        stack pointer
     * @param string $correctVariable correct variable
     * @param File   $phpCsFile       PHP_CodeSniffer File object
     *
     */
    private function fixPhpCsFile(int $stackPtr, string $correctVariable, File $phpCsFile): void
    {
        $phpCsFile->fixer->beginChangeset();
        $phpCsFile->fixer->replaceToken($stackPtr, $correctVariable);
        $phpCsFile->fixer->endChangeset();
    }
}
