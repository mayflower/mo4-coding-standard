<?php

$a = [1, 2, 3];
$b = [
    $a,
    [4, 5, 6],
];

/** @var array $c */
$c = [1, 2];

/**
 * does foo
 *
 * @param array $a
 */
function foo (array $a)
{
    //do stuff
}