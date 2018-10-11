<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Status;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StatusController
 * @package AppBundle\Controller
 */
class DumpStatusController extends Controller
{
    /**
     * @\Symfony\Component\Routing\Annotation\Route("/lucky/")
     */
    public function newAction(Request $request)
    {
        // creates a task and gives it some dummy data for this example
        $status = new Status();




        $form = $this->createFormBuilder($status)
            ->add('title', ChoiceType::class, array(
                'choices' => array(
                    'Choice status:' => array(
                        'In progress' => 'In progress',
                        'Done' => 'Done',
                        'ToDo' => 'ToDo',
                    ))))
            ->add('descript_status', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Change status'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $status = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($status);
            $em->flush();

            /**
             * Matches /lucky/*
             *
             * @Route("/lucky/status", name="blog_show")
             */
            return $this->redirectToRoute('blog_show');
        }

        /* if($form->get('rou')->isSubmitted()){
             return $this->redirectToRoute('blog_show');
         }*/
        return $this->render('lucky/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function showAction()
    {

    }

    /**
     * Matches /lucky/*
     *
     * @Route("/lucky/{id}", name = "blog_elem", requirements={"id"="\d+"})
     */
    public function show($id)
    {

        $status = $this->getDoctrine()
            ->getRepository(Status::class)
            ->find($id);

        if (!$status) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        return $this->render(
            'lucky/element.html.twig',
            array('status' => $status)
        );
    }

    /**
     * Matches /lucky/*
     *
     * @Route("/lucky/{id}", name= "blog_delete", requirements={"id"="\d+"})
     */
    public function elemDelete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $status = $entityManager->getRepository(Status::class)->find($id);

        $entityManager->remove($status);
        $entityManager->flush();
        /**
         * Matches /lucky/*
         *
         * @Route("/lucky/status", name="blog_show")
         */
        return $this->redirectToRoute('blog_show');

    }


}
