<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class FormController extends AbstractController
{

    #[Route('/form', name: 'post_form')]
    public function index(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'action'=>$this->generateUrl('post_form'),
            'method'=>'GET',

        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $doctrine = $this->get('doctrine');
            $em=$doctrine->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_success');
        }

        return $this->renderForm('form/index.html.twig', [
            'post_form' => $form
        ]);
    }
}
