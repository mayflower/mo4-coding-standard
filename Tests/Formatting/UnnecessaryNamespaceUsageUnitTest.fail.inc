<?php

/**
 * test case
 */
namespace BarFail;

use A\B;
use \DOMDocument as D;
use \Exception;
use \DOMComment;

/**
 * Class Foo
 */
class Foo extends \DOMComment
{
    private $a = \DOMDocument::TEST;

    /**
     * method foo
     *
     * @param A\B          $b b
     * @param \DOMDocument $d d
     *
     * @throws \Exception
     */
    public function foo(A\B $b, \DOMDocument $d)
    {
        /** @var \Exception $e */
        $e = new \Exception('ups!');
        throw $e;
    }

    /**
     * returns some awesome \DOMDocument
     *
     * @throws \BarFail\FooException
     */
    public function bar()
    {
        throw new \BarFail\FooException();
    }
}
