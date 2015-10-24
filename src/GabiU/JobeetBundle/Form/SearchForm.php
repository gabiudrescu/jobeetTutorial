<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 21.08.2015
 * Time: 23:23
 */

namespace GabiU\JobeetBundle\Form;

use Symfony\Component\Form\FormBuilderInterface as FormBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class SearchForm {

    /**
     * @var FormBuilder $formBuilder
     */
    private $formBuilder;

    /**
     * @var RouterInterface $router
     */
    private $router;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->formBuilder = $formFactory->createBuilder('form', null, array());
        $this->router = $router;
    }

    public function getSearchForm($query = '')
    {
        $form = $this->formBuilder
            ->add('query', 'text')
            ->add('submit', 'submit')
        ;

        $form->setMethod('GET');
        $form->setAction($this->generateUrl('search', array("query" => $query)));

        return $form->getForm()->createView();
    }

    private function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }
}