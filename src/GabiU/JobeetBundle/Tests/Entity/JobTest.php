<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 25.07.2015
 * Time: 18:38
 */

namespace GabiU\JobeetBundle\Tests\Entity;

use GabiU\JobeetBundle\Entity\Job as Entity;
use GabiU\JobeetBundle\Tests\FixturesTestCase;
use GabiU\JobeetBundle\Utils\Jobeet as Util;

class JobTest extends FixturesTestCase {

    public function testGetCompanySlug()
    {
        $job = $this->getJobEntity();

        $this->assertEquals($job->getCompanySlug(), Util::slugify($job->getCompany()));
    }

    public function testGetPositionSlug()
    {
        $job = $this->getJobEntity();

        $this->assertEquals($job->getPositionSlug(), Util::slugify($job->getPosition()));
    }

    private function getJobEntity()
    {
        /**
         * @var $job Entity
         */
        $job = $this->em->createQuery('SELECT j FROM GabiUJobeetBundle:Job j')
            ->setMaxResults(1)
            ->getSingleResult();

        return $job;
    }

    public function testGetLocationSlug()
    {
        $job = $this->getJobEntity();

        $this->assertEquals($job->getLocationSlug(), Util::slugify($job->getLocation()));
    }

    public function testLifecycleCallbacks()
    {
        /**
         * @var $job Entity
         */
        $job = $this->em->getRepository("GabiUJobeetBundle:Job")
            ->find(1);

        $job->setEmail("somethingElse@blabla.com");

        $job_id = $job->getId();

        $this->em->persist($job);
        $this->em->flush();

        $job = $this->em->getRepository("GabiUJobeetBundle:Job")
            ->find($job_id);

        $this->assertEquals("somethingElse@blabla.com", $job->getEmail());

        $this->assertTrue($job->getUpdatedAt() !== $job->getCreatedAt());
    }
}