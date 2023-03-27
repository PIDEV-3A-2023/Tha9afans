<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementControlleController extends AbstractController
{
    #[Route('/evenement/controlle', name: 'app_evenement_controlle')]
    public function index(): Response
    {
        return $this->render('evenement_controlle/index.html.twig', [
            'controller_name' => 'EvenementControlleController',
        ]);
    }
}
