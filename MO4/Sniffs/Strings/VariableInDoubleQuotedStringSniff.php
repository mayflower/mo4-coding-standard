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
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array<int, string>
     *
     * @see Tokens.php
     */
    #[\Override]
    public function register(): array
    {
        return [T_DOUBLE_QUOTED_STRING];
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint
     *
     * @param File $phpcsFile The PHP_CodeSniffer file where the
     *                        token was found.
     * @param int  $stackPtr  The position in the PHP_CodeSniffer
     *                        file's token stack where the token
     *                        was found.
     *
     * @return void
     */
    #[\Override]
    public function process(File $phpcsFile, $stackPtr): void
    {
        $varRegExp = '/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

        $tokens  = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];

        $matches = [];

        \preg_match_all($varRegExp, $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches as $match) {
            foreach ($match as [$var, $pos]) {
                if (1 !== $pos && '{' === $content[($pos - 1)]) {
                    continue;
                }

                if (\strpos(\substr($content, 0, $pos), '{') > 0
                    && !\str_contains(\substr($content, 0, $pos), '}')
                ) {
                    continue;
                }

                $lastOpeningBrace = \strrpos(\substr($content, 0, $pos), '{');

                if (false !== $lastOpeningBrace
                    && '$' === $content[($lastOpeningBrace + 1)]
                ) {
                    $lastClosingBrace = \strrpos(\substr($content, 0, $pos), '}');

                    if (false !== $lastClosingBrace
                        && $lastClosingBrace < $lastOpeningBrace
                    ) {
                        continue;
                    }
                }

                $fix = $phpcsFile->addFixableError(
                    \sprintf(
                        'must surround variable %s with { }',
                        $var
                    ),
                    $stackPtr,
                    'NotSurroundedWithBraces'
                );

                if (true !== $fix) {
                    continue;
                }

                $correctVariable = $this->surroundVariableWithBraces(
                    $content,
                    $pos,
                    $var
                );

                $this->fixPhpCsFile($stackPtr, $correctVariable, $phpcsFile);
            }
        }
    }

    /**
     * Surrounds a variable with curly brackets
     *
     * @param string $content content
     * @param int    $pos     position
     * @param string $var     variable
     *
     * @return string
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
     * @return void
     */
    private function fixPhpCsFile(int $stackPtr, string $correctVariable, File $phpCsFile): void
    {
        $phpCsFile->fixer->beginChangeset();
        $phpCsFile->fixer->replaceToken($stackPtr, $correctVariable);
        $phpCsFile->fixer->endChangeset();
    }
}
