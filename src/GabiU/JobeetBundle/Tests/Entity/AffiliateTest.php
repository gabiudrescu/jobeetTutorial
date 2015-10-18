<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 27.07.2015
 * Time: 21:22
 */

namespace GabiU\JobeetBundle\Tests\Entity;


use GabiU\JobeetBundle\Entity\Affiliate;

class AffiliateTest extends \PHPUnit_Framework_TestCase {
    public function testEntity()
    {
        $category = $this->getMock('GabiU\JobeetBundle\Entity\Category');

        $affiliate = new Affiliate();

        $props = array(
            "name",
            "url",
            "email",
            "token",
            "isActive",
            "createdAt"
        );

        foreach($props as $prop)
        {
            $getter = sprintf("get%s", ucfirst($prop));
            $setter = sprintf("set%s", ucfirst($prop));

            $this->assertInstanceOf('GabiU\JobeetBundle\Entity\Affiliate', $affiliate->$setter($prop));
            $this->assertEquals($prop, $affiliate->$getter());
        }

        $this->assertNull($affiliate->getId());

        $this->assertInstanceOf('GabiU\JobeetBundle\Entity\Affiliate', $affiliate->addCategory($category));
        $this->assertEquals(1, count($affiliate->getCategories()));

        $affiliate->removeCategory($category);
        $this->assertEquals(0, count($affiliate->getCategories()));

        $affiliate->setCreatedAtValue();

        $this->assertNotNull($affiliate->getCreatedAt());
    }
}
