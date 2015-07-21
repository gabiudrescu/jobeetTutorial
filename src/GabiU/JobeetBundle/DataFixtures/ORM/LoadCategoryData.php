<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 21.07.2015
 * Time: 21:30
 */

namespace GabiU\JobeetBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GabiU\JobeetBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categories = array(
            "category-design" => "Design",
            "category-programming" => "Programming",
            "category-manager" => "Manager",
            "category-administrator" => "Administrator"
        );

        foreach ($categories as $reference => $category)
        {
            $object = new Category();
            $object->setName($category);

            $manager->persist($object);
            $manager->flush();
            $this->addReference($reference, $object);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}