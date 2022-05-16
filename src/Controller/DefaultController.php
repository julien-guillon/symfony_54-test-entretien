<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/hello', name: 'app_hello', methods:"GET")]
    public function hello(): Response
    {
        return $this->json([
            'hello' => 'world!',
        ]);
    }
}
