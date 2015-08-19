<?php

namespace GabiU\JobeetBundle\Controller;

use GabiU\JobeetBundle\Entity\Affiliate;
use GabiU\JobeetBundle\Form\AffiliateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AffiliateController extends Controller
{
    public function newAction()
    {
        $entity = new Affiliate();
        $form = $this->createAffiliateForm($entity);

        return $this->render('GabiUJobeetBundle:Affiliate:new.html.twig', array(
                "affiliate" => $entity,
                "form" => $form->createView()
            )
        );
    }

    private function createAffiliateForm(Affiliate $entity)
    {
        return $this->createForm(new AffiliateType(), $entity, array(
            "method" => "POST",
            "action" => $this->generateUrl("affiliate_create")
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Affiliate();
        $form = $this->createAffiliateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);

            $manager->flush();

            return $this->redirectToRoute("affiliate_wait");
        }

        return $this->render('GabiUJobeetBundle:Affiliate:new.html.twig', array(
                "affiliate" => $entity,
                "form" => $form->createView()
            )
        );
    }

    public function waitAction()
    {
        return $this->render('GabiUJobeetBundle:Affiliate:wait.html.twig', array(
                // ...
            )
        );
    }

}
