<?php

namespace GabiU\JobeetBundle\Controller;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\HttpKernel\Kernel;

class AdminController extends BaseAdminController
{
    public function activationAction()
    {
        $id = $this->request->query->get('id');
        $affiliate = $this->getDoctrine()->getRepository("GabiUJobeetBundle:Affiliate")->find($id);

        try {
            $this
                ->getDoctrine()
                ->getRepository("GabiUJobeetBundle:Affiliate")
                ->activateAffiliate($affiliate);
        } catch (\Exception $e)
        {
            $this->addFlash("danger", "General error:".$e->getMessage().$e->getTraceAsString());
        }

        return $this->redirectToRoute("admin", array(
            "view" => 'edit',
            "entity" => $this->request->query->get('entity')
        ));
    }

    public function extendAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository("GabiUJobeetBundle:Job");

        $id = $this->request->query->get('id');

        $entity = $repository->find($id);
        $entity->extend();

        $em->persist($entity);
        $em->flush();

        $this->addFlash("success", "Entity updated");

        return $this->redirectToRoute('admin', array(
            "view" => 'edit',
            "entity" => $this->request->query->get('entity')
        ));
    }

    public function loginAction()
    {

        $authUtils = $this->get("security.authentication_utils");

        if ($error = $authUtils->getLastAuthenticationError())
        {
            $error = $error->getMessage();
        }

        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createFormBuilder()
            ->add("username","text", array("label" => false, "attr" => array("placeholder" => "Username")))
            ->add("password", "password", array("label" => false, "attr" => array("placeholder" => "Password")))
            ->add("submit", "submit", array("label" => "Login"));

        $form->setAction($this->generateUrl("login_check"))
            ->setMethod("POST");

        return $this->render("@GabiUJobeet/Admin/login.html.twig", array(
            "form" => $form->getForm()->createView(),
            "error" => $error,
            "last_username" => $lastUsername
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntityForm($entity, array $entityProperties, $view)
    {
        $formCssClass = array_reduce($this->config['design']['form_theme'], function ($previousClass, $formTheme) {
            return sprintf('theme_%s %s', strtolower(str_replace('.html.twig', '', basename($formTheme))), $previousClass);
        });
        $formBuilder = $this->createFormBuilder($entity, array(
            'data_class' => $this->entity['class'],
            'attr' => array('class' => $formCssClass, 'id' => $view.'-form'),
        ));
        foreach ($entityProperties as $name => $metadata) {
            $formFieldOptions = array();
            if ('association' === $metadata['fieldType'] && in_array($metadata['associationType'], array(ClassMetadataInfo::ONE_TO_MANY, ClassMetadataInfo::MANY_TO_MANY))) {
                continue;
            }
            if ('collection' === $metadata['fieldType']) {
                $formFieldOptions = array('allow_add' => true, 'allow_delete' => true);
                if (version_compare(Kernel::VERSION, '2.5.0', '>=')) {
                    $formFieldOptions['delete_empty'] = true;
                }
            }
            $formFieldOptions['attr']['field_type'] = $metadata['fieldType'];
            $formFieldOptions['attr']['field_css_class'] = $metadata['class'];
            $formFieldOptions['attr']['field_help'] = $metadata['help'];

            //------------------------------------------------------------------
            //------------------------------------------------------------------
            // Overrides
            if (isset($metadata['choices'])) {
                $formFieldOptions['choices'] = array_combine($metadata['choices'], $metadata['choices']);
            }
            // End overrides
            //------------------------------------------------------------------
            //------------------------------------------------------------------

            $formBuilder->add($name, $metadata['fieldType'], $formFieldOptions);
        }
        return $formBuilder->getForm();
    }
}