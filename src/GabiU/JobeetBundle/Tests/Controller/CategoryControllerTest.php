<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 28.07.2015
 * Time: 20:14
 */

namespace GabiU\JobeetBundle\Tests\Controller;

use GabiU\JobeetBundle\Tests\FunctionalFixturesTestCase;
use GabiU\JobeetBundle\Entity\Category;

class CategoryControllerTest extends FunctionalFixturesTestCase {
    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/category/design");
        $this->assertEquals(
            'GabiU\JobeetBundle\Controller\CategoryController::showAction',
            $client->getRequest()->attributes->get('_controller')
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNonExistingCategoryReturns404()
    {
        $client = static::createClient();

        $crawler = $client->request("GET", "/category/a_non_existing_category");
        $this->assertEquals(
            'GabiU\JobeetBundle\Controller\CategoryController::showAction',
            $client->getRequest()->attributes->get('_controller')
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @var $maxJobsOnCategory integer
     */
    protected $maxJobsOnCategory;

    /**
     * @var $maxJobsOnHomepage integer
     */
    protected $maxJobsOnHomepage;

    public function setup()
    {
        parent::setup();

        $this->maxJobsOnCategory = static::$kernel->getContainer()->getParameter("gabiu.jobeet.max_jobs_on_category");
        $this->maxJobsOnHomepage = static::$kernel->getContainer()->getParameter("gabiu.jobeet.max_jobs_on_category");
    }

    public function testCategoriesOnHomepageAreClickable()
    {
        $client = static::createClient();

        $categories = $this->em->getRepository("GabiUJobeetBundle:Category")->getWithJobs();

        /**
         * @var $category Category
         */
        foreach ($categories as $category)
        {
            /**
             * Access first page
             */
            $crawler = $client->request("GET", "/");
            $jobs_no = $this->em->getRepository("GabiUJobeetBundle:Job")->countActiveJobs($category->getId());
            $categoryLink = $crawler->selectLink($category->getName())->link();

            /**
             * if $jobs_no is bigger than maxJobsOnHomepage settings
             * then we should have a category page with pagination
             */
//            if ($jobs_no > $this->maxJobsOnHomepage)
//            {
//                $moreLink = $crawler->filter(
//                    ".category_".$category->getSlug()." .more_jobs a"
//                )->link();
//
//                $this->assertEquals($categoryLink->getUri(), $moreLink->getUri());
//            }

            $crawler = $client->click($categoryLink);

            $this->assertEquals(
                'GabiU\JobeetBundle\Controller\CategoryController::showAction',
                $client->getRequest()->attributes->get('_controller')
            );
            $this->assertEquals($category->getSlug(), $client->getRequest()->attributes->get('slug'));


            $this->assertTrue($this->maxJobsOnCategory <= $crawler->filter("table.jobs tr")->count());

            $pages = ceil($jobs_no/$this->maxJobsOnCategory);

            $this->assertEquals($crawler->filter("#content > div > div.pagination_desc > strong:nth-child(2)")->text(),"1/".$pages);

            if ($pages > 1)
            {
                for ($i = 2; $i <= $pages; $i++)
                {
                    $nextPage = $crawler->selectLink($i)->link();
                    $crawler = $client->click($nextPage);

                    $this->assertEquals(
                        'GabiU\JobeetBundle\Controller\CategoryController::showAction',
                        $client->getRequest()->attributes->get('_controller')
                    );
                    $this->assertEquals($i, $client->getRequest()->attributes->get('page'));
                    $this->assertTrue($crawler->filter(".jobs tr")->count() <= $this->maxJobsOnCategory);

                    if ($jobs_no > 1)
                    {
                        $this->assertEquals($crawler->filter("#content > div > div.pagination_desc > strong:nth-child(2)")->text(),$i."/".$pages);
                    }
                }
            }
        }
    }
}