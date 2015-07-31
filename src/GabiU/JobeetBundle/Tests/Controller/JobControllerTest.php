<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 28.07.2015
 * Time: 20:38
 */

namespace GabiU\JobeetBundle\Tests\Controller;

use GabiU\JobeetBundle\Utils\Jobeet as Utils;
use GabiU\JobeetBundle\Tests\FunctionalFixturesTestCase;
use GabiU\JobeetBundle\Entity\Job;

class JobControllerTest extends FunctionalFixturesTestCase {
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/");

        $this->assertEquals(
            'GabiU\JobeetBundle\Controller\JobController::indexAction',
            $client->getRequest()->attributes->get("_controller")
        );

        /**
         * test there are no expired jobs in homepage
         */
        $this->assertEquals(0, $crawler->filter(".jobs td.position:contains('Expired')")->count());
    }


    public function testMaxJobsOnHomepage()
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/");

        $maxJobsOnHomepage = static::$kernel->getContainer()->getParameter("gabiu.jobeet.max_jobs_on_homepage");

        /**
         * nextAll() will return a table with all the jobs
         */
        $this->assertEquals($maxJobsOnHomepage, $crawler->filter("div#category_design")->nextAll()->filter("tr")->count());

        /**
         * There's only one category with more than $maxJobsOnHomepage jobs (e.g. 20)
         */
        $this->assertEquals(1, $crawler->filter("div.more_jobs")->count());
    }

    /**
     * @return Job
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getMostRecentProgrammingJob()
    {
        $em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");

        $query = $em->createQuery("SELECT j FROM GabiUJobeetBundle:Job j left join j.category c where c.slug = :slug and j.expiresAt > :date order by j.createdAt DESC");
        $query->setParameter("slug", "programming");
        $query->setParameter("date", Utils::getCurrentDate());
        $query->setMaxResults(1);
        $job = $query->getSingleResult();

        return $job;
    }

    public function testJobsAreOrderedChronologicallyDesc()
    {
        $client = static::createClient();
        $job = $this->getMostRecentProgrammingJob();

        $crawler = $client->request("GET", "/");

        $this->assertTrue(
            $crawler
                ->filter('table.jobs#programming tr')
                ->first()
                ->filter(
                    sprintf('a[href*="/%d/"]', $job->getId()
                    )
                )
                ->count() == 1);
    }

    public function testEachJobOnHomepageIsClickable()
    {
        $client = static::createClient();
        $crawler = $client->request("GET","/");

        $job = $this->getMostRecentProgrammingJob();
        $link = $crawler->selectLink("Web Developer")->first()->link();

        $crawler = $client->click($link);

        $this->assertEquals('GabiU\JobeetBundle\Controller\JobController::showAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals($job->getCompanySlug(), $client->getRequest()->attributes->get('company'));
        $this->assertEquals($job->getPositionSlug(), $client->getRequest()->attributes->get('position'));
        $this->assertEquals($job->getId(), $client->getRequest()->attributes->get('id'));
    }

    public function testANonExistentJobReturns404()
    {
        $client = static::createClient();
        $crawler = $client->request("GET", "/job/foo-inc/milano-italy/0/painter");
        $client->followRedirect();

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}