<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalerieControlleController extends AbstractController
{
    #[Route('/galerie/controlle', name: 'app_galerie_controlle')]
    public function index(): Response
    {
        return $this->render('galerie_controlle/index.html.twig', [
            'controller_name' => 'GalerieControlleController',
        ]);
    }
}
