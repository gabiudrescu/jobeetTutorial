<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 23.08.2015
 * Time: 17:50
 */

namespace GabiU\JobeetBundle\Mailer;

use GabiU\JobeetBundle\Model\MailerObjectInterface;

abstract class AbstractMailer {

    protected $render;

    protected $mailer;

    protected $template;

    protected $message;

    /**
     * @param TemplateRender  $render
     * @param \Swift_Mailer   $mailer
     * @param string          $template
     */
    public function __construct($render, \Swift_Mailer $mailer, $template = "")
    {
        $this->render = $render;
        $this->mailer = $mailer;
        $this->template = $template;
        $this->message = \Swift_Message::newInstance();
    }

    /**
     * @param MailerObjectInterface $entity
     *
     * @return mixed
     */
    abstract public function send(MailerObjectInterface $entity);
}