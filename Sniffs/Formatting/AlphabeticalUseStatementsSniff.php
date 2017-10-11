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

namespace MO4\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Namespaces\UseDeclarationSniff;
use PHP_CodeSniffer\Util\Tokens as PHP_CodeSniffer_Tokens;

class AlphabeticalUseStatementsSniff extends UseDeclarationSniff
{
    /**
     * Last import seen in group
     *
     * @var string
     */
    private $lastImport = '';

    /**
     * Line number of the last seen use statement
     *
     * @var integer
     */
    private $lastLine = -1;

    /**
     * Current file
     *
     * @var string
     */
    private $currentFile = null;


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in
     *                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        parent::process($phpcsFile, $stackPtr);

        if ($this->currentFile !== $phpcsFile->getFilename()) {
            $this->lastLine    = -1;
            $this->lastImport  = '';
            $this->currentFile = $phpcsFile->getFilename();
        }

        $tokens = $phpcsFile->getTokens();
        $line   = $tokens[$stackPtr]['line'];

        // Ignore function () use () {...}.
        $isNonImportUse = $this->checkIsNonImportUse($phpcsFile, $stackPtr);
        if (true === $isNonImportUse) {
            return;
        }

        $currentImportArr = $this->getUseImport($phpcsFile, $stackPtr);
        $currentPtr       = $currentImportArr['startPtr'];
        $currentImport    = $currentImportArr['content'];

        if (($this->lastLine + 1) < $line) {
            $this->lastLine   = $line;
            $this->lastImport = $currentImport;

            return;
        }

        $fixable = false;
        if ($this->lastImport !== ''
            && strcmp($this->lastImport, $currentImport) > 0
        ) {
            $msg     = 'USE statements must be sorted alphabetically';
            $code    = 'MustBeSortedAlphabetically';
            $fixable = $phpcsFile->addFixableError($msg, $currentPtr, $code);
        }

        if (true === $fixable) {
            // Find the correct position in current use block.
            $newDestinationPtr
                = $this->findNewDestination($phpcsFile, $stackPtr, $currentImport);

            $currentUseStr = $this->getUseStatementAsString($phpcsFile, $stackPtr);

            $phpcsFile->fixer->beginChangeset();
            $phpcsFile->fixer->addContentBefore($newDestinationPtr, $currentUseStr);
            $this->fixerClearLine($phpcsFile, $stackPtr);
            $phpcsFile->fixer->endChangeset();
        }//end if

        $this->lastImport = $currentImport;
        $this->lastLine   = $line;

    }//end process()


    /**
     * Get the import class name for use statement pointed by $stackPtr.
     *
     * @param File $phpcsFile PHP CS File
     * @param int  $stackPtr  pointer
     *
     * @return array
     */
    private function getUseImport(File $phpcsFile, $stackPtr)
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

    }//end getUseImport()


    /**
     * Get the full use statement as string, including trailing white space.
     *
     * @param File $phpcsFile PHP CS File
     * @param int  $stackPtr  pointer
     *
     * @return string
     */
    private function getUseStatementAsString(
        File $phpcsFile,
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

    }//end getUseStatementAsString()


    /**
     * Check if "use" token is not used for import.
     * E.g. function () use () {...}.
     *
     * @param File $phpcsFile PHP CS File
     * @param int  $stackPtr  pointer
     *
     * @return bool
     */
    private function checkIsNonImportUse(File $phpcsFile, $stackPtr)
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

    }//end checkIsNonImportUse()


    /**
     * Replace all the token in same line as the element pointed to by $stackPtr
     * the by the empty string.
     * This will delete the line.
     *
     * @param File $phpcsFile PHP CS file
     * @param int  $stackPtr  pointer
     *
     * @return void
     */
    private function fixerClearLine(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $line   = $tokens[$stackPtr]['line'];

        for ($i = ($stackPtr - 1); $tokens[$i]['line'] === $line; $i--) {
            $phpcsFile->fixer->replaceToken($i, '');
        }

        for ($i = $stackPtr; $tokens[$i]['line'] === $line; $i++) {
            $phpcsFile->fixer->replaceToken($i, '');
        }

    }//end fixerClearLine()


    /**
     * Find a new destination pointer for the given import string in current
     * use block.
     *
     * @param File   $phpcsFile PHP CS File
     * @param int    $stackPtr  pointer
     * @param string $import    import string requiring new position
     *
     * @return int
     */
    private function findNewDestination(
        File $phpcsFile,
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
            $prevImportArr = $this->getUseImport($phpcsFile, $prevPtr);
        } while ($prevLine === ($line - 1)
            && (strcmp($prevImportArr['content'], $import) > 0)
        );

        return $ptr;

    }//end findNewDestination()


}//end class
