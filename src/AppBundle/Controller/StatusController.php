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
class StatusController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $status = new Status();

        $form = $this->getStatusForm($status);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $status = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('status_list');

        }
        return $this->render('status/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $status = $this->getDoctrine()
            ->getRepository(Status::class)
            ->find($id);

        $form = $this->getStatusForm($status);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($status);
            $em->flush();

            return $this->redirect($this->generateUrl('status_list'));
        }

        return $this->render('status/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @return Response
     */
    public function viewAction($id)
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
            'status/view.html.twig',
            array('status' => $status)
        );

    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $status = $entityManager->getRepository(Status::class)->find($id);

        $entityManager->remove($status);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('status_list'));
    }

    /**
     * Method return statuses list
     * @return Response
     */
    public function listAction()
    {
        $status = $this->getDoctrine()
            ->getRepository(Status::class)
            ->findAll();

        if (!$status) {
            throw $this->createNotFoundException(
                'No status found'
            );
        }
        return $this->render(
            'status/list.html.twig',
            array('status' => $status)
        );
    }

    /**
     * @param Status $status
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getStatusForm(Status $status)
    {

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
        return $form;
    }

}
