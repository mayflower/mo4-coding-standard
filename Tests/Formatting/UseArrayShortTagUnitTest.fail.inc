<?php

$a = array(1, 2, 3);
$b = array(
    $a,
    [4, 5, 6],
);
$b = array(
    $a,
    array(4, 5, 6),
);