<?php

namespace GabiU\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function showAction($slug)
    {
        return $this->render('GabiUJobeetBundle:Category:show.html.twig', array(
                // ...
            ));    }

}
