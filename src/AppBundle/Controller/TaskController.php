<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Users;

use Symfony\Bridge\Doctrine\Tests\Fixtures\User;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

// ...
// ...
class TaskController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        //$form = $this->getUserForm($user);
        $form = $this->createFormBuilder($task)
            ->add('nameTask', TextType::class)
            ->add('description', TextareaType::class)
            ->add('date_create', DateType::class)
            ->add('date_update', DateType::class)
            ->add('users', EntityType::class, array(
                    'class' => Users::class,
                    'choice_label' => 'first_name',
                    'multiple' => true,
                    'expanded' => true,
                )
            )
            ->add('save', SubmitType::class, array('label' => 'Submit user'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('task_list');

        }
        return $this->render('task/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateAction(Request $request, $id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        $form = $this->getTaskForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('task_list'));
        }
        return $this->render('task/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('task_list'));
    }

    /**
     * @return Response
     */
    public function listAction()
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found'
            );
        }
        return $this->render(
            'task/list.html.twig',
            array('task' => $task)
        );
    }

    /**
     * @param $id
     * @return Response
     */
    public function viewAction($id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        return $this->render(
            'task/view.html.twig',
            array('task' => $task)
        );
    }

    /**
     * @param $task
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getTaskForm($task)
    {
        $form = $this->createFormBuilder($task)
            ->add('nameTask', TextType::class)
            ->add('description', TextareaType::class)
            ->add('date_create', DateType::class)
            ->add('date_update', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit task'))
            ->getForm();
        return $form;
    }
}