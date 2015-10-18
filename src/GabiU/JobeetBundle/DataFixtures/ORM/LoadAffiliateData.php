<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 17.08.2015
 * Time: 11:24
 */

namespace GabiU\JobeetBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GabiU\JobeetBundle\Entity\Affiliate;

class LoadAffiliateData extends AbstractFixture implements  OrderedFixtureInterface {
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $affiliate = new Affiliate();

        $affiliate->setUrl("http://www.google.ro/");
        $affiliate->setEmail("test@test.com");
        $affiliate->setIsActive(true);
        $affiliate->setName('test 1');
        $affiliate->addCategory($manager->merge($this->getReference("category-programming")));

        $manager->persist($affiliate);

        $affiliate = new Affiliate();
        $affiliate->setUrl("/");
        $affiliate->setEmail("test2@test.com");
        $affiliate->setIsActive(false);
        $affiliate->setName('test 2')
        $affiliate->addCategory($manager->merge($this->getReference("category-design")));
        $affiliate->addCategory($manager->merge($this->getReference("category-programming")));

        $manager->persist($affiliate);


        $manager->flush();

        $this->addReference("affiliate", $affiliate);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }

}