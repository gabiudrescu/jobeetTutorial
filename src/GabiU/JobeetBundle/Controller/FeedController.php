<?php

namespace GabiU\JobeetBundle\Controller;

use GabiU\JobeetBundle\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GabiU\JobeetBundle\Entity\Category;

class FeedController extends Controller
{
    /**
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page)
    {
        $perPage = $this->getParameter("gabiu.jobeet.max_jobs_on_homepage");
        $repo = $this->getDoctrine()->getRepository("GabiUJobeetBundle:Job");

        $jobs = $repo->getActiveJobs(null, $perPage, $page * $perPage);

        return $this->render('GabiUJobeetBundle:Feed:index.atom.twig', array(
                "jobs" => $jobs,
                "maxPage" => ceil($repo->countActiveJobs() / $perPage),
                "latestJobDate" => $repo->getLatestJobCreatedAt(),
                "feedId" => sha1($this->generateUrl("gabi_u_jobeet_all_feed", array("page" => $page), true))
            )
        );
    }

    public function categoryAction($slug, $page)
    {
        $perPage = $this->getParameter("gabiu.jobeet.max_jobs_on_category");
        $categoryRepo = $this->getDoctrine()->getRepository("GabiUJobeetBundle:Category");
        $jobRepo = $this->getDoctrine()->getRepository("GabiUJobeetBundle:Job");

        $category = $categoryRepo->findOneBySlug($slug);

        $jobs = $jobRepo->getActiveJobs($category->getId(), $perPage, $page * $perPage);

        return $this->render('GabiUJobeetBundle:Feed:category.atom.twig', array(
                "jobs" => $jobs,
                "category" => $category,
                "maxPage" => ceil($jobRepo->countActiveJobs($category->getId()) / $perPage),
                "latestJobDate" => $jobRepo->getLatestJobCreatedAt($category->getId()),
                "feedId" => sha1($this->generateUrl("gabi_u_jobeet_category_feed", array("slug" => $slug, "page" => $page), true))
            )
        );
    }
}
