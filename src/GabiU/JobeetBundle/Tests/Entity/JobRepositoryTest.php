<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 25.07.2015
 * Time: 19:45
 */

namespace GabiU\JobeetBundle\Tests\Entity;


class JobRepositoryTest extends Setup {
    public function testCountActiveJobs()
    {
        $query = $this->em->createQuery("SELECT c FROM GabiUJobeetBundle:Category c");
        $categories = $query->getResult();

        foreach ($categories as $category) {
            $query = $this->em->createQuery("SELECT COUNT(j.id) FROM GabiUJobeetBundle:Job j where j.category = :category AND j.expiresAt > :date");

            $query->setParameter("category", $category->getId());
            $query->setParameter("date", date("Y-m-d H:i:s", time()));

            $jobs_db = $query->getSingleScalarResult();

            $job_rep = $this->em->getRepository("GabiUJobeetBundle:Job")->countActiveJobs($category->getId());

            $this->assertEquals($job_rep, $jobs_db);
        }
    }
}