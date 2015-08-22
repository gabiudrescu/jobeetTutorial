<?php

namespace GabiU\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    private function searchForm($query = '')
    {
        $form = $this->createFormBuilder()
            ->add('query', 'search', array('attr' => array('id' => 'search_keywords')))
            ->add('submit', 'submit')
        ;

        $form->setMethod('GET');
        $form->setAction($this->generateUrl('search', array("query" => $query)));

        return $form;
    }

    public function searchAction(Request $request, $page = 1)
    {
        $query = $request->get('form')['query'];
        $limit = $this->getParameter('gabiu.jobeet.max_jobs_on_search');

        $results = $this->getDoctrine()->getRepository("GabiUJobeetBundle:Job")->search($query, $limit, $page);
        $lastPage = ceil($results->count() / $limit);

        return $this->render('GabiUJobeetBundle:Search:search.html.twig', array(
                "results" => $results,
                "lastPage" => $lastPage,
                "nextPage" => $page + 1,
                "previousPage" => $page - 1,
                "currentPage" => $page,
                "searchForm" => $this->searchForm($query)->getForm()->createView(),
                "query" => $query
            )
        );
    }

//    /**
//     *
//     * @param Request $request
//     *
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function typeAheadAction(Request $request)
//    {
//        $query = $request->get('term');
//        $limit = $this->getParameter('gabiu.jobeet.max_jobs_on_search');
//
//        $results = $this->getDoctrine()->getRepository("GabiUJobeetBundle:Job")->search($query, $limit, 1);
//
//        return $this->render('@GabiUJobeet/list.html.twig', array(
//            "category" => array(
//                "slug" => 'test',
//                "activeJobs" => $results->getIterator()->getArrayCopy()
//            )
//        ));
//    }

}
