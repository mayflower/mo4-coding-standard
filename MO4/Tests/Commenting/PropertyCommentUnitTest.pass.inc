<?php

/** @var string $bar */
$bar = 'one';

class X
{
    public $publicPropertySelfDescriptive;

    /**
     * @var int
     */
    public $publicProperty = 1; // Random comment
    private $foo = 15; //add
    private $bar = null; //delete
    const MUH = 'KUH'; // even more wat
    private $y = null; /* y is null by default */
    public static $whatever = 'foobar'; // wat
    private $baz = 16; //change

    /**
     * array of foo => bar arrays
     *
     * @var array
     */
    private $_privateProperty = [['foo' => 'bar']];

    /**
     * @var int
     *
     * @ORM\Column(name="SECT_ID", type="integer", nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * foo
     */
    public function foo()
    {
        $foo = 2;
        /** @var int $bla */
        $bla = 1;
        /** @var int $bla */

        return ($bla === $foo); /* valid single line comment */
    }

    private $_endThing;

    /**
     * stuff
     */
    const STUFF = 1;

    public $stuff = [self::STUFF];
}
