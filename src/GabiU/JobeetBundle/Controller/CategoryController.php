<?php

namespace GabiU\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GabiU\JobeetBundle\Entity\Category;

class CategoryController extends Controller
{
    public function showAction($slug, $page)
    {
        $category = $this
            ->container
            ->get('doctrine.orm.entity_manager')
            ->getRepository("GabiUJobeetBundle:Category")
            ->findOneBySlug($slug);

        if (!$category)
        {
            throw $this->createNotFoundException(sprintf("Unable to find %s category", $slug));
        }

        $totalJobs = $this
            ->container
            ->get("doctrine.orm.entity_manager")
            ->getRepository("GabiUJobeetBundle:Job")
            ->countActiveJobs($category->getId())
        ;

        $jobsPerPage = $this
            ->getParameter("gabiu.jobeet.max_jobs_on_category");

        $lastPage = ceil($totalJobs / $jobsPerPage);

        $previousPage = $page > 1 ? $page - 1 : 1;

        $nextPage = $page < $lastPage ? $page + 1 : $lastPage;

        $category->setActiveJobs(
            $this->get("doctrine.orm.entity_manager")
            ->getRepository("GabiUJobeetBundle:Job")
            ->getActiveJobs(
                $category->getId(),
                $jobsPerPage,
                ($page - 1) * $jobsPerPage
            )
        );

        return $this->render('GabiUJobeetBundle:Category:show.html.twig', array(
                "category" => $category,
                "lastPage" => $lastPage,
                "previousPage" => $previousPage,
                "currentPage" => $page,
                "nextPage" => $nextPage,
                "totalJobs" => $totalJobs
            )
        );
    }

}
