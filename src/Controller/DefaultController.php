<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        //redirige vers la listes des articles
        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/hello", name="hello",methods={"GET"})
     */
    public function hello()
    {
        return $this->json(['hello' => 'world!']);
    }
}
