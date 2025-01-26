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

namespace MO4\Sniffs\Formatting;

use MO4\Library\PregLibrary;
use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens as PHP_CodeSniffer_Tokens;

/**
 * Unnecessary Namespace Usage sniff.
 *
 * Full namespace declaration should be skipped in favour of the short declaration.
 *
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @author    Marco Jantke <marco.jantke@gmail.com>
 * @author    Steffen Ritter <steffenritter1@gmail.com>
 *
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 *
 * @license   http://spdx.org/licenses/MIT MIT License
 *
 * @link      https://github.com/mayflower/mo4-coding-standard
 *
 * @psalm-api
 */
class UnnecessaryNamespaceUsageSniff implements Sniff
{
    /**
     * Tokens used in full class name.
     *
     * @var array<int, int>
     */
    private $classNameTokens = [
        T_NS_SEPARATOR,
        T_STRING,
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
        return [T_CLASS];
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
     *
     * @throws RuntimeException
     */
    #[\Override]
    public function process(File $phpcsFile, $stackPtr): void
    {
        $docCommentTags = [
            '@param'  => 1,
            '@return' => 1,
            '@throws' => 1,
            '@var'    => 2,
        ];
        $scanTokens     = [
            T_NS_SEPARATOR,
            T_DOC_COMMENT_OPEN_TAG,
        ];

        $tokens        = $phpcsFile->getTokens();
        $useStatements = $this->getUseStatements($phpcsFile, 0, ($stackPtr - 1));
        $namespace     = $this->getNamespace($phpcsFile, 0, ($stackPtr - 1));

        $nsSep = $phpcsFile->findNext($scanTokens, ($stackPtr + 1));

        while (false !== $nsSep) {
            $classNameEnd = (int) $phpcsFile->findNext(
                $this->classNameTokens,
                $nsSep,
                null,
                true
            );

            if (T_NS_SEPARATOR === $tokens[$nsSep]['code']) {
                if (T_STRING === $tokens[($nsSep - 1)]['code']) {
                    --$nsSep;
                }

                $className = $phpcsFile->getTokensAsString(
                    $nsSep,
                    ($classNameEnd - $nsSep)
                );

                $this->checkShorthandPossible(
                    $phpcsFile,
                    $useStatements,
                    $className,
                    $namespace,
                    $nsSep,
                    ($classNameEnd - 1)
                );
            } else {
                // Doc comment block.
                foreach ($tokens[$nsSep]['comment_tags'] as $tag) {
                    $content = $tokens[$tag]['content'];

                    if (!\array_key_exists($content, $docCommentTags)) {
                        continue;
                    }

                    $next = ($tag + 1);
                    // PHP Code Sniffer will magically add  T_DOC_COMMENT_CLOSE_TAG with empty string content.
                    $lineEnd = $phpcsFile->findNext(
                        [
                            T_DOC_COMMENT_CLOSE_TAG,
                            T_DOC_COMMENT_STAR,
                        ],
                        $next
                    );

                    $docCommentStringPtr = $phpcsFile->findNext(
                        [T_DOC_COMMENT_STRING],
                        $next,
                        (int) $lineEnd
                    );

                    if (false === $docCommentStringPtr) {
                        continue;
                    }

                    $docLine = $tokens[$docCommentStringPtr]['content'];

                    $docLineTokens = PregLibrary::MO4PregSplit(
                        '/\s+/',
                        $docLine,
                        -1,
                        PREG_SPLIT_NO_EMPTY
                    );

                    // phpcs:disable
                    /** @var array<string> $docLineTokens */
                    $docLineTokens = \array_slice(
                        $docLineTokens,
                        0,
                        $docCommentTags[$content]
                    );
                    // phpcs:enable

                    foreach ($docLineTokens as $docLineToken) {
                        // phpcs:disable
                        /** @var array<string> $typeTokens */
                        $typeTokens = PregLibrary::MO4PregSplit(
                            '/\|/',
                            $docLineToken,
                            -1,
                            PREG_SPLIT_NO_EMPTY
                        );
                        // phpcs:enable

                        foreach ($typeTokens as $typeToken) {
                            if (\in_array($typeToken, $useStatements, true)) {
                                continue;
                            }

                            $this->checkShorthandPossible(
                                $phpcsFile,
                                $useStatements,
                                $typeToken,
                                $namespace,
                                $docCommentStringPtr,
                                $docCommentStringPtr,
                                true
                            );
                        }
                    }
                }
            }

            $nsSep = $phpcsFile->findNext($scanTokens, ($classNameEnd + 1));
        }
    }

    /**
     * Get all use statements in range
     *
     * @param File $phpcsFile PHP CS File
     * @param int  $start     start pointer
     * @param int  $end       end pointer
     *
     * @return array
     */
    protected function getUseStatements(File $phpcsFile, int $start, int $end): array
    {
        $useStatements = [];
        $i             = $start;
        $tokens        = $phpcsFile->getTokens();
        $useTokenPtr   = $phpcsFile->findNext(T_USE, $i, $end);

        while (false !== $useTokenPtr) {
            $classNameStart = (int) $phpcsFile->findNext(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($useTokenPtr + 1),
                $end,
                true
            );
            $classNameEnd   = $phpcsFile->findNext(
                $this->classNameTokens,
                ($classNameStart + 1),
                $end,
                true
            );

            if (false === $classNameEnd) {
                break;
            }

            $useEnd = $phpcsFile->findNext(
                [
                    T_SEMICOLON,
                    T_COMMA,
                ],
                $classNameEnd,
                $end
            );

            // Prevent endless loop when 'use ;' is the last use statement.
            if (false === $useEnd) {
                break;
            }

            /** @var int $aliasNamePtr */
            $aliasNamePtr = $phpcsFile->findPrevious(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($useEnd - 1),
                0,
                true
            );

            $length    = ($classNameEnd - $classNameStart);
            $className = $phpcsFile->getTokensAsString($classNameStart, $length);

            $className                 = $this->getFullyQualifiedClassName($className);
            $useStatements[$className] = $tokens[$aliasNamePtr]['content'];
            $i                         = ($useEnd + 1);

            $useTokenPtr = T_COMMA === $tokens[$useEnd]['code'] ? $i : $phpcsFile->findNext(T_USE, $i, $end);
        }

        return $useStatements;
    }

    /**
     * Get the namespace of the current class file
     *
     * @param File $phpcsFile PHP CS File
     * @param int  $start     start pointer
     * @param int  $end       end pointer
     *
     * @return string
     */
    protected function getNamespace(File $phpcsFile, int $start, int $end): string
    {
        $namespace      = (int) $phpcsFile->findNext(T_NAMESPACE, $start, $end);
        $namespaceStart = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($namespace + 1),
            $end,
            true
        );

        if (false === $namespaceStart) {
            return '';
        }

        $namespaceEnd = (int) $phpcsFile->findNext(
            $this->classNameTokens,
            ($namespaceStart + 1),
            $end,
            true
        );

        $nslen = ($namespaceEnd - $namespaceStart);
        $name  = $phpcsFile->getTokensAsString($namespaceStart, $nslen);

        return "\\{$name}\\";
    }

    /**
     * Return the fully qualified class name, e.g. '\Foo\Bar\Faz'
     *
     * @param string $className class name
     *
     * @return string
     */
    private function getFullyQualifiedClassName(string $className): string
    {
        return '\\' !== $className[0] ? "\\{$className}" : $className;
    }

    /**
     * Check if short hand is possible.
     *
     * @param File   $phpcsFile     PHP CS File
     * @param array  $useStatements array with class use statements
     * @param string $className     class name
     * @param string $namespace     name space
     * @param int    $startPtr      start token pointer
     * @param int    $endPtr        end token pointer
     * @param bool   $isDocBlock    true if fixing doc block
     *
     * @return void
     */
    private function checkShorthandPossible(File $phpcsFile, array $useStatements, string $className, string $namespace, int $startPtr, int $endPtr, bool $isDocBlock = false): void
    {
        $msg              = 'Shorthand possible. Replace "%s" with "%s"';
        $code             = 'UnnecessaryNamespaceUsage';
        $fixable          = false;
        $replaceClassName = false;
        $replacement      = '';

        $fullClassName = $this->getFullyQualifiedClassName($className);

        if (\array_key_exists($fullClassName, $useStatements)) {
            $replacement = $useStatements[$fullClassName];

            $data = [
                $className,
                $replacement,
            ];

            $fixable = $phpcsFile->addFixableWarning(
                $msg,
                $startPtr,
                $code,
                $data
            );

            $replaceClassName = true;
        } elseif ('' !== $namespace && \str_starts_with($fullClassName, $namespace)) {
            $replacement = \substr($fullClassName, \strlen($namespace));

            $data    = [
                $className,
                $replacement,
            ];
            $fixable = $phpcsFile->addFixableWarning(
                $msg,
                $startPtr,
                $code,
                $data
            );
        }

        if (true !== $fixable) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();

        if (true === $isDocBlock) {
            $tokens     = $phpcsFile->getTokens();
            $oldContent = $tokens[$startPtr]['content'];
            /** @var string $newContent */
            $newContent = \str_replace($className, $replacement, $oldContent);
            $phpcsFile->fixer->replaceToken($startPtr, $newContent);
        } else {
            for ($i = $startPtr; $i < $endPtr; $i++) {
                $phpcsFile->fixer->replaceToken($i, '');
            }

            if (true === $replaceClassName) {
                $phpcsFile->fixer->replaceToken($endPtr, $replacement);
            }
        }

        $phpcsFile->fixer->endChangeset();
    }
}
