<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 28.07.2015
 * Time: 20:19
 */

namespace GabiU\JobeetBundle\Tests;

use Symfony\Component\BrowserKit\Client;

class FunctionalFixturesTestCase extends FixturesTestCase {
    /**
     * Creates a Client - based on use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    protected static function createClient(array $options = array(), array $server = array())
    {
        static::bootKernel($options);

        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }
}