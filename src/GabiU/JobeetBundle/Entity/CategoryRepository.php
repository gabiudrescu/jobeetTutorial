<?php

namespace GabiU\JobeetBundle\Entity;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use GabiU\JobeetBundle\Utils\Jobeet;
use GabiU\JobeetBundle\Entity\Category;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function getWithJobs()
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c FROM GabiUJobeetBundle:Category c LEFT JOIN  c.jobs j where j.expiresAt > :date"
        )->setParameter('date',Jobeet::getCurrentDate());

        return $query->getResult();
    }

    /**
     * @param $slug
     *
     * @return null|Category
     */
    public function findOneBySlug($slug)
    {
        return $this->findOneBy(array("slug" => $slug));
    }
}
