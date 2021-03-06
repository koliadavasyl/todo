<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Users;

use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $user = new Users();

        //$form = $this->getUserForm($user);

        $form = $this->createFormBuilder($user)
            ->add(
                'first_name',
                TextType::class,
                [
                    'required' => true
                ]
            )->add(
                'last_name',
                TextType::class
            )->add('email', EmailType::class)
            ->add('date_birth', DateType::class)
            ->add('date_create', DateType::class)
            ->add('date_update', DateType::class)
            ->add('task', EntityType::class, array(
                    'class' => Task::class,
                    'choice_label' => 'nameTask',
                    'multiple' => true,
                    'expanded' => true,
                )
            )
            ->add('save', SubmitType::class, array('label' => 'Submit user'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $form != NULL) {

            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_list');

        }

        return $this->render('user/new.html.twig', array(
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
        $user = $this->getDoctrine()
            ->getRepository(Users::class)
            ->find($id);

        $form = $this->getUserForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('user_list'));
        }

        return $this->render('user/new.html.twig', array(
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
        $user = $entityManager->getRepository(Users::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('user_list'));
    }

    /**
     * @return Response
     */
    public function listAction()
    {
        $user = $this->getDoctrine()
            ->getRepository(Users::class)

            ->findAll();

        if (!$user) {
            throw $this->createNotFoundException(
                'No status found'
            );
        }
        return $this->render(
            'user/list.html.twig',
            array('user' => $user)
        );
    }

    /**
     * @param $id
     * @return Response
     */
    public function viewAction($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(Users::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return $this->render(
            'user/view.html.twig',
            array('user' => $user)
        );
    }

    /**
     * @param $user
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getUserForm($user)
    {
        $form = $this->createFormBuilder($user)
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('email', EmailType::class)
            ->add('date_birth', DateType::class)
            ->add('date_create', DateType::class)
            ->add('date_update', DateType::class)
            ->add('task', EntityType::class, array(
                'class' => Task::class,
                'choice_label' => 'nameTask',
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('save', SubmitType::class, array('label' => 'Submit user'))
            ->getForm();
        return $form;
    }

    private function errorAction($form)
    {

        return $this->render('user/error.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}