<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 27.07.2015
 * Time: 19:50
 */

namespace GabiU\JobeetBundle\Tests\Utils;

use GabiU\JobeetBundle\Tests\Entity\DatabaseTestSetup;
use GabiU\JobeetBundle\Utils\Jobeet as Utils;

class getPaginatorTest extends DatabaseTestSetup {

    /**
     * quick and dirty way to assert if Utils getPaginator method
     * returns a Paginator instance - without mocking any objects
     */
    public function testGetPaginator()
    {
        $query = $this->em->createQuery();

        $this->assertInstanceOf('Doctrine\ORM\Tools\Pagination\Paginator', Utils::getPaginator($query));
    }
}