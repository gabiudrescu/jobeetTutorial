<?php

namespace GabiU\JobeetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetjob()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/job');
    }

}
