<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 25.07.2015
 * Time: 18:38
 */

namespace GabiU\JobeetBundle\Tests\Entity;

use GabiU\JobeetBundle\Entity\Job as Entity;
use GabiU\JobeetBundle\Utils\Jobeet as Util;

class JobTest extends Setup {

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

    public function testSetExpiresAtValue()
    {
        $job = new Entity();
        $job->setExpiresAtValue();

        $this->assertEquals(time() + 86400 * 30, $job->getExpiresAt()->format("U"));
    }

}