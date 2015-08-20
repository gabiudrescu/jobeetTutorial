<?php

namespace GabiU\JobeetBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use GabiU\JobeetBundle\Entity\Affiliate;

class ApiController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Get all jobs for your affiliate token",
     *  section="All Jobs",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      401 = "Returned when no token was provided",
     *      403 = "Returned when token is invalid"
     *  }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=false, description="Offset from which to start listing jobs")
     * @Annotations\QueryParam(name="limit", requirements="\d+", nullable=true, default="5", description="Number of jobs per page")
     * @Annotations\QueryParam(name="token", requirements="\b[0-9a-f]{5,40}\b", nullable=false, description="Affiliate token")
     *
     * @Annotations\View(
     *  templateVar="jobs"
     * )
     *
     * @param Request      $request
     * @param ParamFetcher $params
     *
     * @return array
     */
    public function getJobsAction(Request $request, ParamFetcher $params)
    {
        /**
         * @var Affiliate $affiliate
         */
        $affiliate = $this->container->get('security.token_storage')->getToken()->getUsername();
        $jobs = $this->container->get('gabi_u_jobeet.page.handler')->affiliateJobs($params->get('limit'), $params->get('offset'), $affiliate);

        $return['data'] = $jobs->getIterator()->getArrayCopy();
        $return['_navigation']['next'] = $this
            ->generateUrl('api_1_get_jobs', array('limit' => $params->get('limit'), 'offset' => $params->get('offset') + $params->get('limit'), 'token' => $affiliate->getToken()), false);

        return $return;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="View only one job",
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
     * @param string $token
     *
     * @return array
     *
     * @throws NotFoundHttpException when job doesn't exist
     */
    public function getJobAction($id, $token)
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
