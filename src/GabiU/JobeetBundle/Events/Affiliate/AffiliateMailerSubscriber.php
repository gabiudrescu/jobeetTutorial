<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 20.08.2015
 * Time: 22:11
 */

namespace GabiU\JobeetBundle\Events\Affiliate;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use GabiU\JobeetBundle\Events\AbstractMailerSubscriber as AbstractMailer;

class AffiliateMailerSubscriber extends AbstractMailer implements EventSubscriberInterface {

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            'jobeet.affiliate.activated' => 'sendActivationEmail'
        );
    }

}