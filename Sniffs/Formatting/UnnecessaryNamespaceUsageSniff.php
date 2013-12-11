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
 * Unnecessary Namespace Usage sniff.
 *
 * Full namespace declaration should be skipped in favour of the short declaration.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @author    Marco Jantke <marco.jantke@gmail.com>
 * @author    Steffen Ritter <steffenritter1@gmail.com>
 * @copyright 2013 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */
class MO4_Sniffs_Formatting_UnnecessaryNamespaceUsageSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array(int)
     * @see    Tokens.php
     */
    public function register()
    {
        return array(T_CLASS);
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The PHP_CodeSniffer file where the
     *                                        token was found.
     * @param int                  $stackPtr  The position in the PHP_CodeSniffer
     *                                        file's token stack where the token
     *                                        was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $baseMsg = 'Shorthand possible. Replace "%s" with "%s"';

        $tokens = $phpcsFile->getTokens();
        $useStatements = $this->getUseStatements($phpcsFile, 0, $stackPtr - 1);
        $nameSpace = $this->getNameSpace($phpcsFile, 0, $stackPtr - 1);

        $nsSep = $phpcsFile->findNext([T_NS_SEPARATOR, T_DOC_COMMENT], $stackPtr + 1);

        while ($nsSep !== false) {
            $classNameEnd = $phpcsFile->findNext(
                [T_NS_SEPARATOR, T_STRING],
                $nsSep,
                null,
                true
            );

            if ($tokens[$nsSep]['code'] === T_NS_SEPARATOR) {
                if ($tokens[$nsSep - 1]['code'] === T_STRING) {
                    $nsSep -= 1;
                }
                $className = $phpcsFile->getTokensAsString($nsSep, $classNameEnd - $nsSep);

                if (array_key_exists($className, $useStatements)) {
                    $msg = sprintf(
                        $baseMsg,
                        $className,
                        $useStatements[$className]
                    );
                    $phpcsFile->addWarning($msg, $nsSep);
                }

                if (strpos($className, $nameSpace) === 0) {
                    $msg = sprintf(
                        $baseMsg,
                        $className,
                        substr($className, strlen($nameSpace) + 1)
                    );
                    $phpcsFile->addWarning($msg, $nsSep);
                }
            } else {
                $docLine = $tokens[$nsSep]['content'];
                if (preg_match('/\s+@(param|return|throws|var)/', $docLine) ===  1) {
                    foreach ($useStatements as $className => $useName) {
                        $pos    = strpos($docLine, $className);
                        $length = strlen($className);

                        if ($pos !== false) {
                            $endOfComment = substr($docLine, $pos + $length);

                            if (1 === preg_match('/^(\s|\||\*).*/', $endOfComment)) {
                                $msg = sprintf(
                                    $baseMsg,
                                    $className,
                                    $useStatements[$className]
                                );
                                $phpcsFile->addWarning($msg, $nsSep);
                            }
                        }
                    }

                    $pattern = sprintf("/%s(\w+)/", preg_quote($nameSpace));
                    $matches = array();
                    if (preg_match($pattern, $docLine, $matches) === 1) {
                        $msg = sprintf(
                            $baseMsg,
                            $matches[0],
                            $matches[1]
                        );
                        $phpcsFile->addWarning($msg, $nsSep);
                    }
                }

            }

            $nsSep = $phpcsFile->findNext([T_NS_SEPARATOR, T_DOC_COMMENT], $classNameEnd + 1);
        }
    }

    /**
     * get all use statements in range
     *
     * @param PHP_CodeSniffer_File $phpcsFile PHP CS File
     * @param int                  $start     start pointer
     * @param int                  $end       end pointer
     *
     * @return array
     */
    protected  function getUseStatements(PHP_CodeSniffer_File $phpcsFile, $start, $end)
    {
        $useStatements = array();
        $i             = $start;
        $tokens        = $phpcsFile->getTokens();
        $useTokenPtr   = $phpcsFile->findNext(T_USE, $i, $end);

        while ($useTokenPtr !== false) {
            $classNameStart = $phpcsFile->findNext(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                $useTokenPtr + 1,
                $end,
                true
            );
            $classNameEnd = $phpcsFile->findNext(
                [T_NS_SEPARATOR, T_STRING],
                $classNameStart + 1,
                $end,
                true
            );
            $useEnd = $phpcsFile->findNext(
                T_SEMICOLON,
                $classNameEnd,
                $end
            );
            $aliasNamePtr = $phpcsFile->findPrevious(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                $useEnd - 1,
                null,
                true
            );

            $className = $phpcsFile->getTokensAsString($classNameStart, $classNameEnd - $classNameStart);

            $useStatements[$className] = $tokens[$aliasNamePtr]['content'];

            $i = $useEnd + 1;
            $useTokenPtr = $phpcsFile->findNext(T_USE, $i, $end);
        }

        return $useStatements;
    }

    /**
     * get the namespace of the current class file
     *
     * @param PHP_CodeSniffer_File $phpcsFile PHP CS File
     * @param int                  $start     start pointer
     * @param int                  $end       end pointer
     *
     * @return array
     */
    protected  function getNamespace(PHP_CodeSniffer_File $phpcsFile, $start, $end)
    {
        $namespace = $phpcsFile->findNext(T_NAMESPACE, $start, $end);
        $namespaceStart = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            $namespace + 1,
            $end,
            true
        );
        $namespaceEnd = $phpcsFile->findNext(
            [T_NS_SEPARATOR, T_STRING],
            $namespaceStart + 1,
            $end,
            true
        );

        $name = $phpcsFile->getTokensAsString($namespaceStart, $namespaceEnd - $namespaceStart);

        return "\\{$name}\\";
    }
}
 