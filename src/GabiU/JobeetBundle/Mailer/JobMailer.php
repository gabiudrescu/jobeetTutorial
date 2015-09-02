<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 23.08.2015
 * Time: 17:49
 */

namespace GabiU\JobeetBundle\Mailer;


use GabiU\JobeetBundle\Entity\Job;
use GabiU\JobeetBundle\Model\MailerObjectInterface;

class JobMailer extends AbstractMailer {
    /**
     * @param MailerObjectInterface $entity
     *
     * @return mixed
     */
    public function send(MailerObjectInterface $entity)
    {
        /**
         * @var Job $job
         */
        $job = $entity;

        $message = $this->message
            ->setSubject('New job posted: activate now!')
            ->setTo($job->getEmail())
            ->setFrom("gabriel.udr@gmail.com", "Jobeet Administrator")
            ->setBody($this->render->render($this->template, array("job" => $job)), "text/html", "utf-8");

        $this->mailer->send($message);
    }

}