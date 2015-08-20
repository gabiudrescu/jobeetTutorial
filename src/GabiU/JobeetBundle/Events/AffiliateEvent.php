<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 20.08.2015
 * Time: 22:06
 */

namespace GabiU\JobeetBundle\Events;


use GabiU\JobeetBundle\Entity\Affiliate;
use Symfony\Component\EventDispatcher\GenericEvent;

class AffiliateEvent extends GenericEvent {

    public function __construct(Affiliate $affiliate, array $arguments = array())
    {
        parent::__construct($affiliate, $arguments);
    }

    public function getAffiliate()
    {
        return $this->getSubject();
    }
}