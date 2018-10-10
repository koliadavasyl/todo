<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Status;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class StatusController
 * @package AppBundle\Controller
 */
class StatusController extends Controller
{
    /**
     * @\Symfony\Component\Routing\Annotation\Route("/lucky/")
     */
    public function newAction(Request $request)
    {
        // creates a task and gives it some dummy data for this example
        $status = new Status();
        $status->setTitle('Write a blog post');
        $status->setDescriptStatus('write');

        $form = $this->createFormBuilder($status)
            ->add('title', TextType::class)
            ->add('descript_status', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Change status'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $status = $form->getData();

            return $this->redirectToRoute('status_success');
        }
        return $this->render('lucky/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


}
