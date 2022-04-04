<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{

    /**
     *
     * @Route("/hello", name="hello")
     */
    public function hello()
    {
        return new JsonResponse(array('hello' => 'world!'));
    }

}
