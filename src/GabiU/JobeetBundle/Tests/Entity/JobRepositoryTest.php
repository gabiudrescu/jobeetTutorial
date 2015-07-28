<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 25.07.2015
 * Time: 19:45
 */

namespace GabiU\JobeetBundle\Tests\Entity;

use Doctrine\ORM\NoResultException;
use GabiU\JobeetBundle\Utils\Jobeet as Utils;
use GabiU\JobeetBundle\Entity\Category;

class JobRepositoryTest extends DatabaseTestSetup {
    public function testCountActiveJobs()
    {
        $query = $this->em->createQuery("SELECT c FROM GabiUJobeetBundle:Category c");
        $categories = $query->getResult();

        /**
         * @var $category Category
         */
        foreach ($categories as $category) {
            $query = $this->em->createQuery("SELECT COUNT(j.id) FROM GabiUJobeetBundle:Job j where j.category = :category AND j.expiresAt > :date");

            $query->setParameter("category", $category->getId());
            $query->setParameter("date", date("Y-m-d H:i:s", time()));

            $jobs_db = $query->getSingleScalarResult();

            $job_rep = $this->em->getRepository("GabiUJobeetBundle:Job")->countActiveJobs($category->getId());

            $this->assertEquals($job_rep, $jobs_db);

            if ($jobs_db > 2)
            {

            }
        }
    }

    public function testGetActiveJobs()
    {
        $query = $this->em->createQuery('SELECT c from GabiUJobeetBundle:Category c');
        $categories = $query->getResult();

        /**
         * @var $category Category
         */
        foreach ($categories as $category) {
            $query = $this->em->createQuery('SELECT COUNT(j.id) from GabiUJobeetBundle:Job j WHERE j.expiresAt > :date AND j.category = :category');
            $query->setParameter('date', Utils::getCurrentDate());
            $query->setParameter('category', $category->getId());
            $jobs_db = $query->getSingleScalarResult();

            $jobs_rep = $this->em->getRepository('GabiUJobeetBundle:Job')->getActiveJobs($category->getId(), null, null);
            // This test tells if the number of active jobs for a given category from
            // the database is the same as the value returned by the function
            $this->assertEquals($jobs_db, count($jobs_rep));

            $jobs_rep2 = $this->em->getRepository('GabiUJobeetBundle:Job')->getActiveJobs($category->getId(), 4, 4);

            $this->assertFalse($jobs_rep->getQuery()->getSQL() === $jobs_rep2->getQuery()->getSQL());
        }
    }

    public function testGetActiveJob()
    {
        $queries = array(
            "SELECT j from GabiUJobeetBundle:Job j where j.expiresAt > :date",
            "SELECT j from GabiUJobeetBundle:Job j where j.expiresAt < :date"
        );

        $this->assertNotNull($this->getActiveJob($queries[0]));
        $this->assertNull($this->getActiveJob($queries[1]));
    }

    public function testGetActiveJobWithInvalidId()
    {
        $no_job = $this->em->getRepository("GabiUJobeetBundle:Job")->getActiveJob(9999999);

        $this->assertNull($no_job);
    }

    private function getActiveJob($query)
    {
        $query = $this->em->createQuery($query);

        $query->setParameter("date", Utils::getCurrentDate());
        $query->setMaxResults(1);

        try {
            $job_db = $query->getSingleResult();
        } catch (NoResultException $e)
        {
            return null;
        }

        $job_rep = $this->em->getRepository("GabiUJobeetBundle:Job")
            ->getActiveJob($job_db->getId());

        return $job_rep;
    }
}