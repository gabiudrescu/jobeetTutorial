<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 20.08.2015
 * Time: 22:02
 */

namespace GabiU\JobeetBundle\Events;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use GabiU\JobeetBundle\Entity\Affiliate;

class AffiliateSubscriber implements EventSubscriber {

    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        /**
         * @var Affiliate $entity
         */
        $entity = $args->getEntity();
        if ($entity instanceof Affiliate && true === $entity->getIsActive()){
            $this->dispatcher->dispatch('');
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