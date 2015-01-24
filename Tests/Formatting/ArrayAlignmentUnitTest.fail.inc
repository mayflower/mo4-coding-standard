<?php


$a = [
    'one' => 1,
    'four' => 4,
];

$a = [
    'one' => 1,
    2,
    'four' => 4,
];

// bad
$a = [
    'one'   => 1, 'two'  => 2,
    'three' => 1, 'four' => 4,
];

$a = [
    'one' => 1,
    2,
    'four' /* comment */ => 4,
];

$a = [
    'one' => 1,
    2,
    // comment
    'four' => 4,
];

$a = [
    2,
    'four' => 4,
    // comment
    'one' => /* comment */
        1,
];

$a = array(
    'short' => 1,
    'verylongerkey' => 2,
    'x' => 3,
);

$a = [
    'short' => 1,
    'verylongerkey' => 2,
    'x' => 3,
];

$a = array(
    'short'         => 1,
    'verylongerkey' => 2,
    'array'         => array(
        'val1'      => 1,
        'val112'    => 1,
        'val11234567' => 1,
        'v'         => 1,
    ),
    'x'             => 3,
);

$a = [
    'short' => 1,
    'verylongerkey' => 2,
    'array'         => [
        'val1'      => 1,
        'val112'    => 1,
        'val1123456' => 1,
        'v'         => 1,
    ],
    'x'             => 3,
];

$a = [
    'short'         => 1,
    'verylongerkey' => 2,
    'array'         => array(
        'val1'   => 1,
        'val112'  => 1,
        'val11234567' => 1,
        'v' => 1,
    ),
    'x'             => 3,
];

$a = array(
    'short'         => 1,
    'verylongerkey' => 2,
    'array'      => [
        'val1'   => 1,
        'val112'  => 1,
        'val1123' => 1,
        'v'        => 1,
    ],
    'x'             => 3,
);

$a = [
    0 => [ 1 => 'O', 0 => 'O'],
    1 =>  [ 1 => 'O', 0 => 'I'],
    2  => [ 1 => 'I', 0 => 'O'],
    3 => [ 1 => 'I', 0 => 'I'],
];

foreach ($x as $k => $v) {

}

$a = [
    function () {
        $b = [
            'one'  => 1,
            'four' => 4,
        ];
        foreach ($b as $k     => $v) {
        }
        $bar = [
            'one'  => 1,
            'four' => 4,
        ];
        foreach ($bar as $key => $value) {
        }
    },
    function () {
    },
    'one'                     => 1,
    2,
    'four'                    => 2,
    123456789
    /* bla */ => 'fail',
    98765432
    => 'fail',
];

$values  = [
    'Test:1' => [
        'object_id'         => 1,
        'object_class'      => 'Test',
        'object_class_name' => 'TestClass',
        'object_name'       => 'TestObject',
        'id'                => 'Test:1', ],
    'Test:2' => [
        'object_id'         => 2,
        'object_class'      => 'Test',
        'object_class_name' => 'TestClass',
        'object_name'       => 'TestObject',
        'id'                => 'Test:2', ], ];

