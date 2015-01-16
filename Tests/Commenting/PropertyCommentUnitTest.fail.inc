<?php

class X
{
    public $publicPropertySelfDescriptive;

    /** @var int  */
    public $publicProperty = 1;

    /**
     * @varx array
     *
     * array of foo => bar arrays
     */
    private $_privateProperty = [['foo' => 'bar']];

    /**
     * @var
     *
     * foo
     *
     * @var array
     */
    private $_tmi;

    /** stuff */
    private $_moar;
}