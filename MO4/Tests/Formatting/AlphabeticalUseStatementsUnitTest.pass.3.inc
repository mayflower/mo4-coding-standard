<?php

use A;
use B;

$closure = function () use ($b, $a) {
    return $a + $b;
};
