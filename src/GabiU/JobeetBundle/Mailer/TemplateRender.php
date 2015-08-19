<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 20.08.2015
 * Time: 00:10
 */

namespace GabiU\JobeetBundle\Mailer;


use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TemplateRender
 *
 * Used to extract Twig templating engine out of container
 * Injecting it directly rendered a circular reference
 * @see https://github.com/symfony/symfony/issues/2347
 *
 * @package GabiU\JobeetBundle\Mailer
 */
class TemplateRender {

    /**
     * @var EngineInterface
     */
    private $twig;

    public function __construct(ContainerInterface $container)
    {
        $this->twig = $container->get('templating');
    }

    public function render($template, $parameters)
    {
        return $this->twig->render($template, $parameters);
    }
}