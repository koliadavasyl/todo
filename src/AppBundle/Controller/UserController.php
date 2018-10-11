<?php
// src/AppBundle/Controller/LuckyController.php


namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// ...
use AppBundle\Entity\Users;

use Doctrine\ORM\EntityManagerInterface;
// ...
class UserController extends Controller
{

        /**
         * @Route("/users", name="user")
         */

     public function index()
    {
        // вы можете извлечь EntityManager через $this->getDoctrine()
        // или вы можете добавить аргумент в ваше действие: index(EntityManagerInterface $em)
        $em = $this->getDoctrine()->getManager();

        $user = new Users();
        $user->setFirstName('Vasyl');
        $user->setLastName('Koliada');
        $user->setEmail('vasyl.koliada@gmail.com');
        $user->setDateBirth(new \DateTime('12/24/1996'));
        $user->setDateUpdate(new \DateTime("now"));
        $user->setDateCreate(new \DateTime("now"));
        // скажите Doctrine, что вы (в итоге) хотите сохранить Товар (пока без запросов)
        $em->persist($user);

        // на самом деле выполнить запросы (т.е. запрос INSERT)
        $em->flush();

        return new Response('Saved new product with id '.$user->getId());
    }
        /**
        * @Route("/users/{id}", name="users")
        */
        public function showAction($id)
        {
            $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->find($id);

            if (!$user) {
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }

            return new Response('First Name :  '.$user->getFirstName(). '<br/>Last Name: '.$user->getLastName().'<br/>Email:'.$user->getEmail().'<br/>');

    }
}