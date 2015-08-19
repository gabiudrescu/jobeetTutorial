<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 19.08.2015
 * Time: 17:47
 */

namespace GabiU\JobeetBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use GabiU\JobeetBundle\Entity\Affiliate;
use GabiU\JobeetBundle\Mailer\AffiliateMailer;
use Monolog\Logger;

class AffiliateEmailerListener {
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger, AffiliateMailer $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Affiliate && true === $entity->getIsActive())
        {
            try {
                $this->mailer->send($entity);
            } catch (\Exception $e)
            {
                $this->logger->addError(sprintf("General error: %s; %s",$e->getMessage(),$e->getTraceAsString()));
            }
        }
    }
}