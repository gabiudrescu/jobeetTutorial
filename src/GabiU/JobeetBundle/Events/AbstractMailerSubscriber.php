<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 23.08.2015
 * Time: 17:58
 */

namespace GabiU\JobeetBundle\Events;

use GabiU\JobeetBundle\Mailer\AbstractMailer;
use Symfony\Component\EventDispatcher\GenericEvent;

abstract class AbstractMailerSubscriber {
    private $mailer;

    function __construct(AbstractMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendActivationEmail(GenericEvent $event)
    {
        $this->mailer->send($event->getSubject());
    }
}