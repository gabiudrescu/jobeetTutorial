<?php

namespace GabiU\JobeetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;

use GabiU\JobeetBundle\Entity\Job;
use GabiU\JobeetBundle\Entity\Category;

use GabiU\JobeetBundle\Form\JobType;

/**
 * Job controller.
 *
 */
class JobController extends Controller
{
    private function searchForm($query = '')
    {
        $form = $this->createFormBuilder()
            ->add('query', 'text', array('attr' => array('id' => 'search_keywords')))
            ->add('submit', 'submit')
        ;

        $form->setMethod('GET');
        $form->setAction($this->generateUrl('search', array("query" => $query)));

        return $form->getForm();
    }


    /**
     * Lists all Job entities.
     *
     */
    public function indexAction(Request $request)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository("GabiUJobeetBundle:Category")->getWithJobs();

        /**
         * @var $category Category
         */
        foreach($categories as $category)
        {
            $category->setActiveJobs(
                $em->getRepository("GabiUJobeetBundle:Job")->getActiveJobs(
                    $category->getId(), $this->getParameter("gabiu.jobeet.max_jobs_on_homepage")
                )
            );

            $category->setMoreJobs(
                $em->getRepository("GabiUJobeetBundle:Job")
                    ->countActiveJobs($category->getId()) - $this->getParameter("gabiu.jobeet.max_jobs_on_homepage")
            );
        }

        return $this->render('GabiUJobeetBundle:Job:index.html.twig', array(
            'categories' => $categories,
            'searchForm' => $this->searchForm()->createView()
        ));
    }
    /**
     * Creates a new Job entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Job();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash("notice", "Job created! Approve now:");

            return $this->redirect($this->generateUrl('job_wait', array('token' => $entity->getToken())));
        }

        return $this->render(
            '@GabiUJobeet/Job/form.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Job entity.
     *
     * @param Job $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Job $entity)
    {
        $form = $this->createForm(new JobType(), $entity, array(
            'action' => $this->generateUrl('job_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Job entity.
     *
     */
    public function newAction()
    {
        $entity = new Job();
        $form   = $this->createCreateForm($entity);

        return $this->render(
            '@GabiUJobeet/Job/form.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function waitAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository("GabiUJobeetBundle:Job")->findOneBy(array("token" => $token));

        if (!$entity)
        {
            throw $this->createNotFoundException("Unable to find Job entity.");
        }

        $email = $entity->getEmail();

        try {
            $provider = $this->get('gabi_u_jobeet.webmailGuesser')->guess($email);
        } catch (\Exception $e)
        {
            $provider = false;
            $this->get('logger')->addError($e->getMessage(), array($e->getFile(), $e->getLine(), $e->getTraceAsString()));
        }

        return $this->render('@GabiUJobeet/Job/wait.html.twig', array(
            'email' => $email,
            'provider' => $provider
        ));
    }

    /**
     * Finds and displays a Job entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GabiUJobeetBundle:Job')->getActiveJob($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        return $this->render('GabiUJobeetBundle:Job:show.html.twig', array(
            'entity'      => $entity
        ));
    }

    public function previewAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository("GabiUJobeetBundle:Job")->findOneBy(array("token" => $token));

        if (!$entity)
        {
            throw $this->createNotFoundException("Unable to find Job entity.");
        }

        $deleteForm = $this->createDeleteForm($token);
        $publishForm = $this->createPublishForm($token);

        return $this->render("@GabiUJobeet/Job/show.html.twig", array(
            "entity" => $entity,
            "deleteForm" => $deleteForm->createView(),
            "publishForm" => $publishForm->createView()
        ));
    }

    private function createPublishForm($token)
    {
        return $this->createFormBuilder(array("token" => $token))
            ->setAction($this->generateUrl("job_publish", array("token" => $token)))
            ->add("token", 'hidden')
            ->add("submit", "submit", array("label" => "Publish"))
            ->getForm();
    }

    public function publishAction($token)
    {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        /** @var Job $entity */
        $entity = $em->getRepository("GabiUJobeetBundle:Job")->findOneBy(array("token" => $token));

        if (!$entity)
        {
            throw $this->createNotFoundException("Unable to find Job entity.");
        }

        $entity->setIsActivated(true);

        try {
            $em->persist($entity);
            $em->flush($entity);
            $this->addFlash("notice", "Job published!");
        } catch (\Exception $e){
            $this->addFlash("error", $e->getMessage());
        }

        return $this->redirectToRoute("job_show", array(
            "company" => $entity->getCompanySlug(),
            "location" => $entity->getLocationSlug(),
            "id" => $entity->getId(),
            "position" => $entity->getPositionSlug()
        ));
    }

    /**
     * Displays a form to edit an existing Job entity.
     *
     */
    public function editAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GabiUJobeetBundle:Job')->findOneBy(array("token" => $token));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($token);

        return $this->render(
            '@GabiUJobeet/Job/form.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Job entity.
    *
    * @param Job $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Job $entity)
    {
        $form = $this->createForm(new JobType(), $entity, array(
            'action' => $this->generateUrl('job_update', array('token' => $entity->getToken())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Edit'));

        return $form;
    }
    /**
     * Edits an existing Job entity.
     *
     */
    public function updateAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GabiUJobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($token);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('job_preview', array('token' => $token)));
        }

        return $this->render('GabiUJobeetBundle:Job:form.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Job entity.
     *
     */
    public function deleteAction(Request $request, $token)
    {
        $form = $this->createDeleteForm($token);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GabiUJobeetBundle:Job')->findOneByToken($token);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        $this->addFlash("notice", "# $token Job deleted");
        return $this->redirect($this->generateUrl('gabi_u_jobeet_homepage'));
    }

    /**
     * Creates a form to delete a Job entity by token.
     *
     * @param string $token The job token
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($token)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job_delete', array('token' => $token)))
            ->setMethod('DELETE')
            ->add('token', 'hidden')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
