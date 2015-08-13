<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 13.08.2015
 * Time: 13:32
 */

namespace GabiU\JobeetBundle\Handler;

use GabiU\JobeetBundle\Model\JobInterface;

interface JobHandlerInterface {

    /**
     * Get a Job given the ID
     *
     * @api
     *
     * @param int $id
     *
     * @return JobInterface
     */
    public function get($id);

    /**
     * Get a paginated list of jobs
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Create a new Job
     *
     * @param array $parameters
     *
     * @return JobInterface
     */
    public function post(array $parameters);

    /**
     * Edit a job
     *
     * @param JobInterface $job
     * @param array        $parameters
     *
     * @return JobInterface
     */
    public function put(JobInterface $job, array $parameters);

    /**
     * Partially update a job
     *
     * @param JobInterface $job
     * @param array        $parameters
     *
     * @return JobInterface
     */
    public function patch(JobInterface $job, array $parameters);
}