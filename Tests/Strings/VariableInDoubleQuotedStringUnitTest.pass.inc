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
$j = "InputFile: {$adapter->getComputedPath($inputFile->getKey())}\n";
$k = "{$a[$b]}";
$l = "{$a[$b->foo()]}";
$m = "{$a[$b->foo($c)]}";
$n = "{$a[$b[0]][$b[0]]}";
$o = "{$a['foo'][$b[0]]}";
$p = "{$a[$b->foo($c['foo'][$d[0]])]}";
$p = "{$good[$fail]}";
