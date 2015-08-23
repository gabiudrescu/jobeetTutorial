<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 23.08.2015
 * Time: 17:42
 */

namespace GabiU\JobeetBundle\Events\Job;


use GabiU\JobeetBundle\Entity\Job;
use Symfony\Component\EventDispatcher\GenericEvent;

class JobEvent extends GenericEvent {

    public function __construct(Job $job, array $arguments = array()){
        parent::__construct($job, $arguments);
    }

    public function getJob()
    {
        return $this->getSubject();
    }
}