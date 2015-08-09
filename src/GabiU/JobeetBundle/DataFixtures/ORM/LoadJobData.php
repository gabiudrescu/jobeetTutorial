<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 21.07.2015
 * Time: 21:36
 */

namespace GabiU\JobeetBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GabiU\JobeetBundle\Entity\Job;

class LoadJobData extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 100; $i <= 105; $i++)
        {
            $jobs_sensio_labs = new Job();
            $jobs_sensio_labs->setCategory($manager->merge($this->getReference("category-programming")));
            $jobs_sensio_labs->setType("full-time");
            $jobs_sensio_labs->setCompany("Sensio Labs" . $i);
            $jobs_sensio_labs->setLogo("sensio-labs.gif");
            $jobs_sensio_labs->setUrl("http://www.sensiolabs.com/");
            $jobs_sensio_labs->setPosition("Web Developer");
            $jobs_sensio_labs->setLocation("Paris, France");
            $jobs_sensio_labs->setDescription("You've already developed websites with symfony and you want to work with Open-Source technologies. You have a minimum of 3 years experience in web development with PHP or Java and you wish to participate to development of Web 2.0 sites using the best frameworks available.");
            $jobs_sensio_labs->setHowToApply("Send your resume to fabien[a]sensio.com");
            $jobs_sensio_labs->setIsPublic(true);
            $jobs_sensio_labs->setIsActivated(true);
            $jobs_sensio_labs->setEmail("gabriel.udr@gmail.com");
            $jobs_sensio_labs->setExpiresAt(new \DateTime("+30 days"));

            $manager->persist($jobs_sensio_labs);
        }

        for ($i = 100; $i <= 130; $i++)
        {
            $job_extreme_sensio = new Job();
            $job_extreme_sensio->setCategory($manager->merge($this->getReference('category-design')));
            $job_extreme_sensio->setType('part-time');
            $job_extreme_sensio->setCompany('Extreme Sensio ' . $i);
            $job_extreme_sensio->setLogo('extreme-sensio.gif');
            $job_extreme_sensio->setUrl('http://www.extreme-sensio.com/');
            $job_extreme_sensio->setPosition('Web Designer');
            $job_extreme_sensio->setLocation('Paris, France');
            $job_extreme_sensio->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in.');
            $job_extreme_sensio->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
            $job_extreme_sensio->setIsPublic(true);
            $job_extreme_sensio->setIsActivated(false);
            $job_extreme_sensio->setEmail('job@example.com');
            $job_extreme_sensio->setExpiresAt(new \DateTime('+30 days'));

            $manager->persist($job_extreme_sensio);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}