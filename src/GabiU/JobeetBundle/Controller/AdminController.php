<?php

namespace GabiU\JobeetBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends BaseAdminController
{
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
}