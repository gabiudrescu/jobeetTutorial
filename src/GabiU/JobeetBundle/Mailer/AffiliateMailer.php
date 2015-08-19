<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 19.08.2015
 * Time: 18:16
 */

namespace GabiU\JobeetBundle\Mailer;


use GabiU\JobeetBundle\Entity\Affiliate;
use Symfony\Component\Templating\EngineInterface;

class AffiliateMailer {

    private $render;

    private $mailer;

    private $template;

    private $message;

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

    public function send(Affiliate $affiliate)
    {
        $message = $this->message
            ->setSubject('Jobeet affiliate token')
            ->setTo($affiliate->getEmail())
            ->setFrom("gabriel.udr@gmail.com", "Jobeet Administrator")
            ->setBody($this->render->render($this->template, array('affiliate' => $affiliate)), "text/html", "utf-8")
        ;

        $this->mailer->send($message);
    }
}