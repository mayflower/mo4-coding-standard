<?php

use A;
use B;
use C;

use A\B\A as AA;
use A\B\B as BB;
use A\B\C as CC;

use Z;

use X;

class Foo {
    $bar = 'foo';

    $foo = function () use ($bar) {
        //do sth.
    };

    $foo2 = function () /*comment */ use ($bar) {
        //do sth.
    };
}