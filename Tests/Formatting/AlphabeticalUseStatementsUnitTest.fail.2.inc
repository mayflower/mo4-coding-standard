<?php
// take care, more than one fix will be applied
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use MyApp\Entity\Dull;
use MyApp\Tests\TestCase,
    MyApp\Tests\AdvancedTestCase;
use MyApp\Bar\Dull,
    MyApp\Bar\DullHelper,
    MyApp\Bar\DullAdvancedHelper,
    MyApp\Bar\Foo,
    MyApp\Bar\FooRepository,
    MyApp\Entity\DullRepository,
    MyApp\Validator\ParameterContainer;
use MyApp\Validator\GetParameterContainer;

class Foo
{

}
