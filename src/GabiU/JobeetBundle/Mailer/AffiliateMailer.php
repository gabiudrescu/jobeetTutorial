<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 19.08.2015
 * Time: 18:16
 */

namespace GabiU\JobeetBundle\Mailer;

use GabiU\JobeetBundle\Entity\Affiliate;
use GabiU\JobeetBundle\Model\MailerObjectInterface;

class AffiliateMailer extends AbstractMailer{
    /**
     * @param MailerObjectInterface $entity
     *
     * @return mixed
     */
    public function send(MailerObjectInterface $entity)
    {
        /**
         * @var Affiliate $affiliate
         */
        $affiliate = $entity;

        $message = $this->message
            ->setSubject('Jobeet affiliate token')
            ->setTo($affiliate->getEmail())
            ->setFrom("gabriel.udr@gmail.com", "Jobeet Administrator")
            ->setBody($this->render->render($this->template, array('affiliate' => $affiliate)), "text/html", "utf-8")
        ;

        $this->mailer->send($message);
    }


}