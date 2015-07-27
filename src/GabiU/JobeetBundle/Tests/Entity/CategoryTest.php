<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 27.07.2015
 * Time: 21:00
 */

namespace GabiU\JobeetBundle\Tests\Entity;

use GabiU\JobeetBundle\Entity\Category;
use GabiU\JobeetBundle\Entity\Job;
use GabiU\JobeetBundle\Entity\Affiliate;

class CategoryTest extends \PHPUnit_Framework_TestCase {
    public function testEntity()
    {
        $job = $this->getMock('GabiU\JobeetBundle\Entity\Job');
        $affiliate = $this->getMock('GabiU\JobeetBundle\Entity\Affiliate');

        $category = new Category();

        $category->addJob($job);
        $this->assertEquals(1, count($category->getJobs()));

        $category->removeJob($job);
        $this->assertEquals(0, count($category->getJobs()));

        $category->addAffiliate($affiliate);
        $this->assertEquals(1, count($category->getAffiliates()));

        $category->removeAffiliate($affiliate);
        $this->assertEquals(0, count($category->getAffiliates()));

        $this->assertEquals("", (string) $category);

        $this->assertNull($category->getId());

        $category->setName("Bla");
        $category->setSlugValue();

        $this->assertEquals("bla", $category->getSlug());


        $category->setMoreJobs(3);
        $this->assertNotNull($category->getMoreJobs());

        $this->assertInstanceOf('GabiU\JobeetBundle\Entity\Category', $category->setActiveJobs(array()));
        $this->assertInternalType("array", $category->getActiveJobs());
    }
}