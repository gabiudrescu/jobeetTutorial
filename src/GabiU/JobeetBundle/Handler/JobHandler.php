<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 13.08.2015
 * Time: 13:37
 */

namespace GabiU\JobeetBundle\Handler;


use Doctrine\Common\Persistence\ObjectManager;
use GabiU\JobeetBundle\Entity\Affiliate;
use GabiU\JobeetBundle\Model\JobInterface;
use GabiU\JobeetBundle\Entity\JobRepository;

class JobHandler implements JobHandlerInterface {

    private $objectManager;
    private $entityClass;

    /**
     * @var JobRepository
     */
    private $repository;

    function __construct(ObjectManager $objectManager, $entityClass)
    {
        $this->objectManager = $objectManager;
        $this->entityClass = $entityClass;
        $this->repository = $this->objectManager->getRepository($this->entityClass);
    }

    public function affiliateJobs($limit = 5, $offset = 0, Affiliate $affiliate)
    {
        return $this->repository->getActiveJobs(null, $limit, $offset, $affiliate->getId());
    }

    /**
     * Get a Job given the ID
     *
     * @api
     *
     * @param int $id
     *
     * @return JobInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a paginated list of jobs
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        // TODO: Implement all() method.
    }

    /**
     * Create a new Job
     *
     * @param array $parameters
     *
     * @return JobInterface
     */
    public function post(array $parameters)
    {
        // TODO: Implement post() method.
    }

    /**
     * Edit a job
     *
     * @param JobInterface $job
     * @param array        $parameters
     *
     * @return JobInterface
     */
    public function put(JobInterface $job, array $parameters)
    {
        // TODO: Implement put() method.
    }

    /**
     * Partially update a job
     *
     * @param JobInterface $job
     * @param array        $parameters
     *
     * @return JobInterface
     */
    public function patch(JobInterface $job, array $parameters)
    {
        // TODO: Implement patch() method.
    }
}