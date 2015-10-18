<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 20.08.2015
 * Time: 22:02
 */

namespace GabiU\JobeetBundle\Events\Affiliate;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use GabiU\JobeetBundle\Entity\Affiliate;

/**
 * Class AffiliateSubscriber
 *
 * Based on this idea: http://stackoverflow.com/a/30138554/2215758
 *
 * @package GabiU\JobeetBundle\Events
 */
class AffiliateSubscriber implements EventSubscriber {

    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        /**
         * @var Affiliate $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof Affiliate && true === $entity->getIsActive()){
            $this->dispatcher->dispatch('jobeet.affiliate.activated', new AffiliateEvent($entity));
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
            "postUpdate"
        );
    }

}