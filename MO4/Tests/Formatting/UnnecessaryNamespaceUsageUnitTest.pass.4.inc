<?php

use Another\Foo;
use Foo as GlobalFoo;

class Bar
{
    /**
     * Do a thing
     *
     * @param Foo $foo
     */
    public function doThing(Foo $foo)
    {
        $foo->bar();
    }
}
