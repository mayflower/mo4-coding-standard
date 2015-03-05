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

    }//end register()


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
        $docCommentTags = array(
                           '@param',
                           '@return',
                           '@throws',
                           '@var',
                          );
        $classRe        = '[\w\x7f-\xff]';

        $baseMsg = 'Shorthand possible. Replace "%s" with "%s"';

        $tokens        = $phpcsFile->getTokens();
        $useStatements = $this->getUseStatements($phpcsFile, 0, ($stackPtr - 1));
        $nameSpace     = $this->getNameSpace($phpcsFile, 0, ($stackPtr - 1));

        $nsSep = $phpcsFile->findNext(
            [
             T_NS_SEPARATOR,
             T_DOC_COMMENT_OPEN_TAG,
            ],
            ($stackPtr + 1)
        );

        while ($nsSep !== false) {
            $classNameEnd = $phpcsFile->findNext(
                [
                 T_NS_SEPARATOR,
                 T_STRING,
                ],
                $nsSep,
                null,
                true
            );

            if ($tokens[$nsSep]['code'] === T_NS_SEPARATOR) {
                if ($tokens[($nsSep - 1)]['code'] === T_STRING) {
                    $nsSep -= 1;
                }

                $className     = $phpcsFile->getTokensAsString($nsSep, ($classNameEnd - $nsSep));
                $fullClassName = $this->_getFullyQualifiedClassName($className);

                if ((array_key_exists($fullClassName, $useStatements)) === true) {
                    $msg = sprintf(
                        $baseMsg,
                        $className,
                        $useStatements[$fullClassName]
                    );
                    $phpcsFile->addWarning($msg, $nsSep);
                }

                // TODO test.
                if (strpos($fullClassName, $nameSpace) === 0) {
                    $msg = sprintf(
                        $baseMsg,
                        $className,
                        substr($fullClassName, strlen($nameSpace))
                    );
                    $phpcsFile->addWarning($msg, $nsSep);
                }
            } else {
                foreach ($tokens[$nsSep]['comment_tags'] as $tag) {
                    if ((in_array($tokens[$tag]['content'], $docCommentTags)) === false) {
                        continue;
                    }

                    $lineEnd = $phpcsFile->findNext(
                        [
                         T_DOC_COMMENT_CLOSE_TAG,
                         T_DOC_COMMENT_STAR,
                        ],
                        ($tag + 1)
                    );
                    $docLine = $phpcsFile->getTokensAsString($tag, ($lineEnd - $tag));
                    foreach ($useStatements as $fullClassName => $useName) {
                        $className = substr($fullClassName, 1);

                        $pos    = strpos($docLine, $className);
                        $length = strlen($className);

                        if ($pos !== false) {
                            if (1 === preg_match("/$classRe/", $docLine[($pos - 1)])) {
                                continue;
                            }

                            $endOfComment = substr($docLine, ($pos + $length));

                            if (1 === preg_match('/^(\s|\||\*).*/', $endOfComment)) {
                                // Ignore isomorph imports, like "use Exception;".
                                if (($className === $useName) && ($docLine[($pos - 1)] !== '\\')) {
                                    continue;
                                }

                                $msg = sprintf(
                                    $baseMsg,
                                    $className,
                                    $useStatements[$fullClassName]
                                );
                                $phpcsFile->addWarning($msg, $tag);
                            }
                        }//end if
                    }//end foreach

                    $pattern = sprintf("/%s(\w+)/", preg_quote($nameSpace));
                    $matches = array();
                    if (preg_match($pattern, $docLine, $matches) === 1) {
                        $msg = sprintf(
                            $baseMsg,
                            $matches[0],
                            $matches[1]
                        );
                        $phpcsFile->addWarning($msg, $tag);
                    }
                }//end foreach
            }//end if

            $nsSep = $phpcsFile->findNext([T_NS_SEPARATOR, T_DOC_COMMENT_OPEN_TAG], ($classNameEnd + 1));
        }//end while

    }//end process()


    /**
     * Get all use statements in range
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
        $i           = $start;
        $tokens      = $phpcsFile->getTokens();
        $useTokenPtr = $phpcsFile->findNext(T_USE, $i, $end);

        while ($useTokenPtr !== false) {
            $classNameStart = $phpcsFile->findNext(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($useTokenPtr + 1),
                $end,
                true
            );
            $classNameEnd   = $phpcsFile->findNext(
                [
                 T_NS_SEPARATOR,
                 T_STRING,
                ],
                ($classNameStart + 1),
                $end,
                true
            );
            $useEnd         = $phpcsFile->findNext(
                T_SEMICOLON,
                $classNameEnd,
                $end
            );
            $aliasNamePtr   = $phpcsFile->findPrevious(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($useEnd - 1),
                null,
                true
            );

            $className = $phpcsFile->getTokensAsString($classNameStart, ($classNameEnd - $classNameStart));

            $className = $this->_getFullyQualifiedClassName($className);
            $useStatements[$className] = $tokens[$aliasNamePtr]['content'];
            $i           = ($useEnd + 1);
            $useTokenPtr = $phpcsFile->findNext(T_USE, $i, $end);
        }//end while

        return $useStatements;

    }//end getUseStatements()


    /**
     * Get the namespace of the current class file
     *
     * @param PHP_CodeSniffer_File $phpcsFile PHP CS File
     * @param int                  $start     start pointer
     * @param int                  $end       end pointer
     *
     * @return array
     */
    protected  function getNamespace(PHP_CodeSniffer_File $phpcsFile, $start, $end)
    {
        $namespace      = $phpcsFile->findNext(T_NAMESPACE, $start, $end);
        $namespaceStart = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($namespace + 1),
            $end,
            true
        );

        if (false === $namespaceStart) {
            return '';
        }

        $namespaceEnd = $phpcsFile->findNext(
            [
             T_NS_SEPARATOR,
             T_STRING,
            ],
            ($namespaceStart + 1),
            $end,
            true
        );

        $name = $phpcsFile->getTokensAsString($namespaceStart, ($namespaceEnd - $namespaceStart));

        return "\\{$name}\\";

    }//end getNamespace()


    /**
     * Return the fully qualified class name, e.g. '\Foo\Bar\Faz'
     *
     * @param string $className class name
     *
     * @return string
     */
    private function _getFullyQualifiedClassName($className)
    {
        if ($className[0] !== '\\') {
            $className = "\\{$className}";

            return $className;
        }

        return $className;

    }//end _getFullyQualifiedClassName()


}//end class
