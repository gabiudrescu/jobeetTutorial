<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 23.08.2015
 * Time: 18:06
 */

namespace GabiU\JobeetBundle\Events\Job;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use GabiU\JobeetBundle\Entity\Job;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobSubscriber implements EventSubscriber {

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Job and false === $entity->getIsActivated())
        {
            $this->dispatcher->dispatch('jobeet.job.activate', new JobEvent($entity));
        }
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            "postPersist"
        );
    }

}