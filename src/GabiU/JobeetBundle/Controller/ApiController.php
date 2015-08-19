<?php

namespace GabiU\JobeetBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Gets a job based on its ID",
     *  output="GabiU\JobeetBundle\Entity\Job",
     *  statusCodes={
     *      200 = "Returned when successful",
     *      404 = "Returned when the page is not found"
     *  }
     * )
     *
     * @Annotations\View("templateVar=job")
     *
     * @param int $id
     *
     * @return array
     *
     * @throws NotFoundHttpException when job doesn't exist
     */
    public function getJobAction($id)
    {
        return $this->getOr404($id);
    }

    protected function getOr404($id){
        if (!$job = $this->container->get("gabi_u_jobeet.page.handler")->get($id)){
            throw $this->createNotFoundException(sprintf("The API resource '%s' was not found. ", $id));
        }

        return $job;
    }

}
