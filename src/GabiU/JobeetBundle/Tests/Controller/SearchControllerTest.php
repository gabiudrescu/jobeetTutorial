<?php

namespace GabiU\JobeetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search');
    }

    public function testTypeahead()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/typeAhead');
    }

}
