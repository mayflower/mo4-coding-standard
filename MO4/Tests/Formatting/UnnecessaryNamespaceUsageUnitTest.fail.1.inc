<?php

/**
 * test case
 */
namespace BarFail;

use A\B;
use A\Be;
use \DOMDocument as D;
use Exception;
use \DOMComment;
use Closure as MyClosure;
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
     * @param \DOMDocument|int|A\B $f f
     *
     * @throws \Exception
     */
    public function foo(A\B $b, \DOMDocument $d, $f)
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
        /** @var A\Be $g */
        $g = new A\Be();
        throw new \BarFail\FooException();
    }

    /**
     * foo
     *
     * @param \Closure $c
     *
     * @return void
     */
    public function myClosureFoo(\Closure $c)
    {
    }
}
