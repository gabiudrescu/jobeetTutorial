<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 25.07.2015
 * Time: 19:41
 */

namespace GabiU\JobeetBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Doctrine\ORM\EntityManager as Em;

class FixturesTestCase extends KernelTestCase {

    /**
     * @var $em Em
     */
    protected $em;

    protected $application;

    public function setup()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->application = new Application(static::$kernel);

        $command = new DropDatabaseDoctrineCommand();
        $this->application->add($command);

        $input = new ArrayInput(array(
            "command" => "doctrine:database:drop",
            "--force" => true
        ));

        $command->run($input, new NullOutput());

        $connection = $this->application->getKernel()->getContainer()->get('doctrine')->getConnection();
        if($connection->isConnected())
        {
            $connection->close();
        }

        $command = new CreateDatabaseDoctrineCommand();
        $this->application->add($command);

        $input = new ArrayInput(array(
            "command" => "doctrine:database:create"
        ));
        $command->run($input, new NullOutput());

        $command = new CreateSchemaDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            "command" => "doctrine:schema:create"
        ));

        $command->run($input, new NullOutput());

        $this->em = static::$kernel->getContainer()
            ->get("doctrine.orm.entity_manager");

        $loader = new ContainerAwareLoader(static::$kernel->getContainer());
        $loader->loadFromDirectory(static::$kernel->locateResource("@GabiUJobeetBundle/DataFixtures/ORM"));

        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }


    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }
}