<?php

/**
 * test case
 */
namespace BarPass;

use A\B;
use \DOMDocument as D;
use \Exception;
use \DOMComment;

/**
 * Class Foo
 */
class Foo extends DOMComment
{
    private $a = D::TEST;

    /**
     * method foo
     *
     * @param B $b b
     * @param D $d d
     * @param D|int|B $f f
     *
     * @throws Exception
     */
    public function foo(B $b, D $d, $f)
    {
        /** @var Exception $e */
        $e = new Exception('ups!');
        throw $e;
    }

    /**
     * returns some awesome \DOMDocument
     *
     * @see \DOMDocument
     *
     * @return D
     */
    public function bar()
    {
        /** @var A\Be $g */
        $g = new A\Be();
        return new D();
    }
}
