<?php

use Doctrine\ORM\EntityManager,
    Doctrine\DBAL\Connection;

use MyApp\OmgException;
use MyApp\FatalException;
use \MyApp\DamnItException;

class MyFixtures
{
    /**
     * Inserts data into database.
     * @throws
     * @param Doctrine\ORM\EntityManager|\Something\ObjectManager $manager Entity Manager
     * @throws MyApp\OmgException
     * @throws \MyApp\FatalException
     * @throws MyApp\DamnItException
     */
    private function insertData($manager)
    {
        /** @var \Doctrine\ORM\EntityManager $manager */
        /** @var \Doctrine\DBAL\Connection $con */
        /**
         * @var MyApp\OmgException|\MyApp\FatalException|\MyApp\DamnItException|OmgException $e
         */
    }
}
