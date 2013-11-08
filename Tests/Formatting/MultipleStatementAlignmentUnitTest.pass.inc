<?php

const CONST1    = 2;
const CONSTLONG = 3;

$a   = 1;
$bb  = 2;
$ccc = 3;
$a2  = 1;
$x[] = 4;

$blafasel = 1;
$bb       = 2;
$ccc      = 3;
$a2       = 1;
$x[]      = 4;

function foo($p1 = 1, $p22 = 2)
{
    $a   = 1;
    $bb  = 2;
    $ccc = 3;
    $a2  = 1;
    $x[] = 4;

    $blafaseln = 1;
    $bb        = 2;
    $ccc       = 3;
    $a /* a */ = 1;
    $x[]       = 4;
}

function bar(
    $p1 = 1,
    $p22 = 2
) {
}