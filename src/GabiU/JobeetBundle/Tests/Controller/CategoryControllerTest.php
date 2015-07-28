<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 28.07.2015
 * Time: 20:14
 */

namespace GabiU\JobeetBundle\Tests\Controller;

use GabiU\JobeetBundle\Tests\FunctionalFixturesTestCase;

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


        $crawler = $client->request("GET", "/category/a_non_existing_category");
        $this->assertEquals(
            'GabiU\JobeetBundle\Controller\CategoryController::showAction',
            $client->getRequest()->attributes->get('_controller')
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}