# MO4 CodeSniffer ruleset 

Provides a CodeSniffer ruleset

* MO4 standard

Requires

* [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [Symfony Coding Standard](https://github.com/xalopp/symfony-coding-standard)

## Installation

1. Install phpcs:

        pear install PHP_CodeSniffer

2. Find your PEAR directory:

        pear config-show | grep php_dir

3. Copy, symlink or check out this repo to a folder called Symfony inside the
   phpcs `Standards` directory:

        cd /path/to/pear/PHP/CodeSniffer/Standards
        git clone git://github.com/xalopp/symfony-coding-standard.git Symfony
        git clone git://github.com/Mayflower/mo4-coding-standard.git MO4

4. Select the Symfony ruleset as your default coding standard:

        phpcs --config-set default_standard MO4

5. Profit

        phpcs path/to/my/file.php

## Contributing

If you do contribute code to these sniffs, please make sure it conforms to the PEAR coding standard and that the unit tests still pass.

To check the coding standard, run from the Symfony-coding-standard source root:

        phpcs --ignore=Tests --standard=PEAR . -n

The unit-tests are run from within the PHP_CodeSniffer directory

* get the [CodeSniffer repository](https://github.com/squizlabs/PHP_CodeSniffer)
* get the [Symfony Coding Standard](https://github.com/xalopp/symfony-coding-standard) and symlink, copy or clone it at CodeSniffer/Standards/Symfony
* symlink, copy or clone this repository at CodeSniffer/Standards/MO4
* from the CodeSniffer repository root run `phpunit --filter Symfony_ tests/AllTests.php`

## Credit


## Licence

Copyright (c) 2013 Xaver Loppenstedt

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

