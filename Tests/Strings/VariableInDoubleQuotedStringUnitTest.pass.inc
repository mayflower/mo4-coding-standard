<?php

$a = "\$a";
$a = "\\\$a";
$a = "blafasel \\\\\$a";
$a = 'blafasel $a';
$b = "blafasel \$a";
$c = "blafasel {$a}";
$d = "blafasel {$_a}";
$e = "100$";
$f = "100$ needed";
$g = "100{$missing} needed";
$h = "100$, plz.";
$i = "100$100, plz.";
