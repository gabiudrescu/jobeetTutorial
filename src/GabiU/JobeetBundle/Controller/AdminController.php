<?php

namespace GabiU\JobeetBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

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
            $this->addFlash("error", "General error:".$e->getMessage().$e->getTraceAsString());
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
}