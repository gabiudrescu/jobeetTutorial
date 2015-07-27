<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 27.07.2015
 * Time: 19:38
 */

namespace GabiU\JobeetBundle\Tests\Entity;

use GabiU\JobeetBundle\Utils\Jobeet as Utils;

class CategoryRepositoryTest extends Setup {
    public function testGetWithJobs()
    {
        $query = $this->em->createQuery("SELECT c FROM GabiUJobeetBundle:Category c LEFT JOIN c.jobs j where j.expiresAt > :date");
        $query->setParameter("date", Utils::getCurrentDate());

        $categories_db = $query->getResult();

        $categories_rep = $this->em->getRepository("GabiUJobeetBundle:Category")
            ->getWithJobs();

        $this->assertEquals(count($categories_rep), count($categories_db));
    }

    public function testFindExistingOneBySlug()
    {
        $category = $this->em->getRepository("GabiUJobeetBundle:Category")
            ->findOneBySlug("design");

        $this->assertNotNull($category);
    }

    public function testFindNotExistingOneBySlug()
    {
        $category = $this->em->getRepository("GabiUJobeetBundle:Category")
            ->findOneBySlug("nonexistingcategory");

        $this->assertNull($category);
    }
}