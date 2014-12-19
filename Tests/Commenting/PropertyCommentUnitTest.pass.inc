<?php

/** @var string $bar */
$bar = 'one';

class X
{
    public $publicPropertySelfDescriptive;

    /**
     * @var int
     */
    public $publicProperty = 1;

    /**
     * array of foo => bar arrays
     *
     * @var array
     */
    private $_privateProperty = [['foo' => 'bar']];

    /**
     * foo
     */
    public function foo()
    {
        $foo = 2;
        /** @var int $bla */
        $bla = 1;
        /** @var int $bla */
    }

    private $_endThing;
}