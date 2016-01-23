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
use Symfony\Component\DomCrawler\Crawler;

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
        $this->assertEquals($maxJobsOnHomepage, $crawler->filter("div#Design")->count());

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

    public function testJobForm()
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/job/new");
        $this->assertContains('GabiU\JobeetBundle\Controller\JobController::newAction', $client->getRequest()->attributes->get('_controller'));

        $formData = array(
            "gabiu_jobeetbundle_job[category]" => 1, //aka Design
            "gabiu_jobeetbundle_job[type]" => "freelance",
            "gabiu_jobeetbundle_job[company]" => "Sensio Labs",
            "gabiu_jobeetbundle_job[logoFile]" => __DIR__.'/../../../../../web/bundles/gabiujobeet/images/sensio-labs.gif',
            "gabiu_jobeetbundle_job[position]" => "Senior designer",
            "gabiu_jobeetbundle_job[url]" => "https://sensiolabs.com/",
            "gabiu_jobeetbundle_job[location]" => "Bucharest",
            "gabiu_jobeetbundle_job[description]" => "Lorem ipsum description",
            "gabiu_jobeetbundle_job[howToApply]" => "Send email to jobs@sensiolabs.com",
            "gabiu_jobeetbundle_job[isPublic]" => true,
            "gabiu_jobeetbundle_job[email]" => 'jobs@sensiolabs.com',
        );

        $form = $crawler->selectButton("Create")->form($formData);

        $client->submit($form);
        $this->assertContains('GabiU\JobeetBundle\Controller\JobController::createAction', $client->getRequest()->attributes->get('_controller'));


        $client->followRedirect();
        $this->assertContains('GabiU\JobeetBundle\Controller\JobController::previewAction', $client->getRequest()->attributes->get('_controller'));
    }

    /**
     * there are three errors we're looking after:
     * 1. description is not set
     * 2. how to apply is not set
     * 3. email is not valid
     *
     * isPublic is defaulting to true as per Doctrine Entity configuration and it will not show an error
     */
    public function testJobFormCreateShowsErrorIfNotFullyCompleted()
    {
        $formData = array(
            "gabiu_jobeetbundle_job[category]" => 1, //aka Design
            "gabiu_jobeetbundle_job[type]" => "freelance",
            "gabiu_jobeetbundle_job[company]" => "Sensio Labs",
            "gabiu_jobeetbundle_job[logoFile]" => __DIR__.'/../../../../../web/bundles/gabiujobeet/images/sensio-labs.gif',
            "gabiu_jobeetbundle_job[position]" => "Senior designer",
            "gabiu_jobeetbundle_job[url]" => "https://sensiolabs.com/",
            "gabiu_jobeetbundle_job[location]" => "Bucharest",
            "gabiu_jobeetbundle_job[email]" => 'not.an.email',
        );
        $client = $this->createJob($formData);

        $crawler = $client->getCrawler();

        /**
         * get HTML errors as a plain message
         */
        $errors = implode("\n\n\n\"", $crawler->filter(".error_list")->each(function(Crawler $crawler){
            return $crawler->parents()->html();
        }));
        $this->assertEquals(3, $crawler->filter(".error_list")->count(), $errors);

        $this->assertEquals(1, $crawler->filter("#gabiu_jobeetbundle_job_howToApply")->siblings()->first()->filter(".error_list")->count());
        $this->assertEquals(1, $crawler->filter("#gabiu_jobeetbundle_job_description")->siblings()->first()->filter(".error_list")->count());
        $this->assertEquals(0, $crawler->filter("#gabiu_jobeetbundle_job_isPublic")->siblings()->first()->filter(".error_list")->count());

        $this->assertEquals(1, $crawler->filter("#gabiu_jobeetbundle_job_email")->siblings()->first()->filter(".error_list")->count());
    }

    private function createJob($values = array()){
        $client = static::createClient();

        $crawler = $client->request("GET", "/job/new");

        $formData = array(
            "gabiu_jobeetbundle_job[category]" => 1, //aka Design
            "gabiu_jobeetbundle_job[type]" => "freelance",
            "gabiu_jobeetbundle_job[company]" => "Sensio Labs",
            "gabiu_jobeetbundle_job[logoFile]" => __DIR__.'/../../../../../web/bundles/gabiujobeet/images/sensio-labs.gif',
            "gabiu_jobeetbundle_job[position]" => "Senior designer",
            "gabiu_jobeetbundle_job[url]" => "https://sensiolabs.com/",
            "gabiu_jobeetbundle_job[location]" => "Bucharest",
//            "gabiu_jobeetbundle_job[description]" => "Lorem ipsum description",
//            "gabiu_jobeetbundle_job[howToApply]" => "Send email to jobs@sensiolabs.com",
//            "gabiu_jobeetbundle_job[isPublic]" => true,
            "gabiu_jobeetbundle_job[email]" => 'test@test.com',
        );

        $form = $crawler->selectButton("Create")->form(array_merge($formData, $values));
        $client->submit($form);

        return $client;
    }

    public function testPublishJob()
    {
        $formData = array_merge($this->formData, array("gabiu_jobeetbundle_job[position]" => "F001"));
        $client = $this->createJob($formData);
        $client->followRedirect();


        $crawler = $client->getCrawler();
        $form = $crawler->selectButton("Publish")->form();

        $client->submit($form);

        $query = $this->em->createQuery("select count(j.id) from GabiUJobeetBundle:Job j where j.position = :position and j.isActivated = 1");
        $query->setParameter("position", "F001");
        $this->assertEquals(1, $query->getSingleScalarResult());
    }

    private $formData = array(
        "gabiu_jobeetbundle_job[description]" => "Lorem ipsum description",
        "gabiu_jobeetbundle_job[howToApply]" => "Send email to jobs@sensiolabs.com",
        "gabiu_jobeetbundle_job[isPublic]" => true,
    );

    public function testDeleteJob()
    {
        $formData = array_merge($this->formData, array("gabiu_jobeetbundle_job[position]" => "F002"));
        $client = $this->createJob($formData);
        $client->followRedirect();


        $crawler = $client->getCrawler();
        $form = $crawler->selectButton("Delete")->form();

        $client->submit($form);

        $query = $this->em->createQuery("select count(j.id) from GabiUJobeetBundle:Job j where j.position = :position");
        $query->setParameter("position", "F002");
        $this->assertEquals(0, $query->getSingleScalarResult());
    }
}