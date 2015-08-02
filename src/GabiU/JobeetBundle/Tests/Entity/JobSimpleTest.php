<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 27.07.2015
 * Time: 20:41
 */

namespace GabiU\JobeetBundle\Tests\Entity;

use GabiU\JobeetBundle\Entity\Job as Entity;

class JobSimpleTest extends \PHPUnit_Framework_TestCase {
    public function testSetExpiresAtValue()
    {
        $job = new Entity();
        $job->setExpiresAtValue();

        $this->assertEquals(time() + 86400 * 30, $job->getExpiresAt()->format("U"));
    }

    public function testGetCategories()
    {
        $job = new Entity();

        $this->assertNull($job->getCategory());
    }
    public function testGetters()
    {
        $job = new Entity();

        $props = array(
            "type",
            "logo",
            "url",
            "description",
            "howToApply",
            "token",
            "isPublic",
            "isActivated",
            "email"
        );

        foreach ($props as $property)
        {
            $setter = sprintf("set%s", ucfirst($property));
            $getter = sprintf("get%s", ucfirst($property));

            $job->$setter($property);

            $this->assertEquals($property, $job->$getter());
        }
    }

    public function testGetTypes()
    {
        $this->assertArrayHasKey("full-time", Entity::getTypes());
        $this->assertContains("Freelance", Entity::getTypes());
        $this->assertContains("full-time", Entity::getTypeValues());
    }
}