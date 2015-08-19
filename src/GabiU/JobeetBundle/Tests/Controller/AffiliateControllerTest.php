<?php

namespace GabiU\JobeetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AffiliateControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new');
    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/create');
    }

    public function testWait()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/wait');
    }

}
