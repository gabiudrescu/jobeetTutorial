<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 25.07.2015
 * Time: 18:16
 */

namespace GabiU\JobeetBundle\Tests\Utils;

use GabiU\JobeetBundle\Utils\Jobeet as Utils;

class JobeetTest extends \PHPUnit_Framework_TestCase {

    public function testSlugify()
    {
        $this->assertEquals('sensio', Utils::slugify("sensio"));
        $this->assertEquals('sensio-labs', Utils::slugify("sensio labs"));
        $this->assertEquals("paris-france", Utils::slugify("paris, france"));
        $this->assertEquals("sensio", Utils::slugify(" sensio"));
        $this->assertEquals("sensio", Utils::slugify("sensio "));
        $this->assertEquals("n-a", Utils::slugify(""));
    }

    public function testGetCurrentDate()
    {
        $this->assertStringStartsWith((string) 2, (string) Utils::getCurrentDate());

        $time = new \DateTime("now");

        $this->assertEquals($time->format("Y-m-d H:i:s"), Utils::getCurrentDate());
    }
}
