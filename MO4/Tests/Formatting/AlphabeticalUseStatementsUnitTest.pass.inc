<?php

use A;
use B;
use C;

use A\B\A as AA;
use A\B\B as BB;
use A\B\C as CC;

use Z;

use X;

use A\B;
use A\B\C;
use A\BD;

// phpcs:set MO4.Formatting.AlphabeticalUseStatements order string

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;

use ExampleSub;
use Examples;

// phpcs:set MO4.Formatting.AlphabeticalUseStatements order string-case-insensitive

use E2xamples;
use E2xampleSub;

// phpcs:set MO4.Formatting.AlphabeticalUseStatements order string-locale

use E3xampleSub;
use E3xamples;

// phpcs:set MO4.Formatting.AlphabeticalUseStatements order dictionary

class Foo {
    $bar = 'foo';

    $foo = function () use ($bar) {
        //do sth.
    };

    $foo2 = function () /*comment */ use ($bar) {
        //do sth.
    };

    // test run away tokens
    use () {};
}
