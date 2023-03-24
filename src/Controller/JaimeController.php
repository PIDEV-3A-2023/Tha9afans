<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JaimeController extends AbstractController
{
    #[Route('/jaime', name: 'app_jaime')]
    public function index(): Response
    {
        return $this->render('jaime/index.html.twig', [
            'controller_name' => 'JaimeController',
        ]);
    }
}
