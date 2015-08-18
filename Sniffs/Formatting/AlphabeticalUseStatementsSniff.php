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
 * Alphabetical Use Statements sniff.
 *
 * Use statements must be in alphabetical order, grouped by empty lines
 *
 * @category  PHP
 * @package   PHP_CodeSniffer-MO4
 * @author    Xaver Loppenstedt <xaver@loppenstedt.de>
 * @author    Steffen Ritter <steffenritter1@gmail.com>
 * @author    Christian Albrecht <christian.albrecht@mayflower.de>
 * @copyright 2013-2014 Xaver Loppenstedt, some rights reserved.
 * @license   http://spdx.org/licenses/MIT MIT License
 * @link      https://github.com/Mayflower/mo4-coding-standard
 */
class MO4_Sniffs_Formatting_AlphabeticalUseStatementsSniff
    extends PSR2_Sniffs_Namespaces_UseDeclarationSniff
{
    /**
     * Last import seen in group
     *
     * @var string
     */
    private $_lastImport = '';

    /**
     * Line number of the last seen use statement
     *
     * @var int
     */
    private $_lastLine = -1;

    /**
     * Current file
     *
     * @var string
     */
    private $_currentFile = null;


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        parent::process($phpcsFile, $stackPtr);

        if ($this->_currentFile !== $phpcsFile->getFilename()) {
            $this->_lastLine    = -1;
            $this->_lastImport  = '';
            $this->_currentFile = $phpcsFile->getFilename();
        }

        $tokens = $phpcsFile->getTokens();
        $line   = $tokens[$stackPtr]['line'];

        // Ignore function () use () {...}.
        $isNonImportUse = $this->_checkIsNonImportUse($phpcsFile, $stackPtr);
        if (true === $isNonImportUse) {
            return;
        }

        $currentImportArr = $this->_getUseImport($phpcsFile, $stackPtr);
        $currentPtr       = $currentImportArr['startPtr'];
        $currentImport    = $currentImportArr['content'];

        if (($this->_lastLine + 1) < $line) {
            $this->_lastLine   = $line;
            $this->_lastImport = $currentImport;

            return;
        }

        $fixable = false;
        if ($this->_lastImport !== ''
            && strcmp($this->_lastImport, $currentImport) > 0
        ) {
            $msg     = 'USE statements must be sorted alphabetically';
            $code    = 'MustBeSortedAlphabetically';
            $fixable = $phpcsFile->addFixableError($msg, $currentPtr, $code);
        }

        if (true === $fixable) {
            // Find the correct position in current use block.
            $newDestinationPtr
                = $this->_findNewDestination($phpcsFile, $stackPtr, $currentImport);

            $currentUseStr = $this->_getUseStatementAsString($phpcsFile, $stackPtr);

            $phpcsFile->fixer->beginChangeset();
            $phpcsFile->fixer->addContentBefore($newDestinationPtr, $currentUseStr);
            $this->_fixerClearLine($phpcsFile, $stackPtr);
            $phpcsFile->fixer->endChangeset();
        }//end if

        $this->_lastImport = $currentImport;
        $this->_lastLine   = $line;

    }//end process()


    /**
     * Get the import class name for use statement pointed by $stackPtr.
     *
     * @param PHP_CodeSniffer_File $phpcsFile PHP CS File
     * @param int                  $stackPtr  pointer
     *
     * @return array
     */
    private function _getUseImport(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $importTokens = array(
                         T_NS_SEPARATOR,
                         T_STRING,
                        );

        $start  = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($stackPtr + 1),
            null,
            true
        );
        $end    = $phpcsFile->findNext($importTokens, $start, null, true);
        $import = $phpcsFile->getTokensAsString($start, ($end - $start));

        return array(
                'startPtr' => $start,
                'content'  => $import,
               );

    }//end _getUseImport()


    /**
     * Get the full use statement as string, including trailing white space.
     *
     * @param PHP_CodeSniffer_File $phpcsFile PHP CS File
     * @param int                  $stackPtr  pointer
     *
     * @return string
     */
    private function _getUseStatementAsString(
        PHP_CodeSniffer_File $phpcsFile,
        $stackPtr
    ) {
        $tokens = $phpcsFile->getTokens();

        $useEndPtr = $phpcsFile->findNext(T_SEMICOLON, ($stackPtr + 2));
        $useLength = ($useEndPtr - $stackPtr + 1);
        if ($tokens[($useEndPtr + 1)]['code'] === T_WHITESPACE) {
            $useLength++;
        }

        $useStr = $phpcsFile->getTokensAsString($stackPtr, $useLength);

        return $useStr;

    }//end _getUseStatementAsString()


    /**
     * Check if "use" token is not used for import.
     * E.g. function () use () {...}.
     *
     * @param PHP_CodeSniffer_File $phpcsFile PHP CS File
     * @param int                  $stackPtr  pointer
     *
     * @return bool
     */
    private function _checkIsNonImportUse(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $prev = $phpcsFile->findPrevious(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($stackPtr - 1),
            null,
            true,
            null,
            true
        );

        if (false !== $prev) {
            $prevToken = $tokens[$prev];

            if ($prevToken['code'] === T_CLOSE_PARENTHESIS) {
                return true;
            }
        }

        return false;

    }//end _checkIsNonImportUse()


    /**
     * Replace all the token in same line as the element pointed to by $stackPtr
     * the by the empty string.
     * This will delete the line.
     *
     * @param PHP_CodeSniffer_File $phpcsFile PHP CS file
     * @param int                  $stackPtr  pointer
     *
     * @return void
     */
    private function _fixerClearLine(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $line   = $tokens[$stackPtr]['line'];

        for ($i = ($stackPtr - 1); $tokens[$i]['line'] === $line; $i--) {
            $phpcsFile->fixer->replaceToken($i, '');
        }

        for ($i = $stackPtr; $tokens[$i]['line'] === $line; $i++) {
            $phpcsFile->fixer->replaceToken($i, '');
        }

    }//end _fixerClearLine()


    /**
     * Find a new destination pointer for the given import string in current
     * use block.
     *
     * @param PHP_CodeSniffer_File $phpcsFile PHP CS File
     * @param int                  $stackPtr  pointer
     * @param string               $import    import string requiring new position
     *
     * @return int
     */
    private function _findNewDestination(
        PHP_CodeSniffer_File $phpcsFile,
        $stackPtr,
        $import
    ) {
        $tokens = $phpcsFile->getTokens();

        $line     = $tokens[$stackPtr]['line'];
        $prevLine = false;
        $prevPtr  = $stackPtr;
        do {
            $ptr = $prevPtr;
            // Use $line for the first iteration.
            if ($prevLine !== false) {
                $line = $prevLine;
            }

            $prevPtr = $phpcsFile->findPrevious(T_USE, ($ptr - 1));
            if ($prevPtr === false) {
                break;
            }

            $prevLine      = $tokens[$prevPtr]['line'];
            $prevImportArr = $this->_getUseImport($phpcsFile, $prevPtr);
        } while ($prevLine === ($line - 1)
            && (strcmp($prevImportArr['content'], $import) > 0)
        );

        return $ptr;

    }//end _findNewDestination()


}//end class
