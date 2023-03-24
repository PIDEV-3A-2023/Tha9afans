<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionControlleController extends AbstractController
{
    #[Route('/session/controlle', name: 'app_session_controlle')]
    public function index(): Response
    {
        return $this->render('session_controlle/index.html.twig', [
            'controller_name' => 'SessionControlleController',
        ]);
    }
}
