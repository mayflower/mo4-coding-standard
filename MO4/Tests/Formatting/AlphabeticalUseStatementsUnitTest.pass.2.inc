<?php

use A;
use B;

class Foo
{
    use ZebraTrait;
    use AlphaTrait;
}

class Bar
{
    /** @use GenericTrait<string> */
    use GenericTrait;
    /** @use AnotherTrait<int> */
    use AnotherTrait;
}

