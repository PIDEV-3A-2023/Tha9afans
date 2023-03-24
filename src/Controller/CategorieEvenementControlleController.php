<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieEvenementControlleController extends AbstractController
{
    #[Route('/categorie/evenement/controlle', name: 'app_categorie_evenement_controlle')]
    public function index(): Response
    {
        return $this->render('categorie_evenement_controlle/index.html.twig', [
            'controller_name' => 'CategorieEvenementControlleController',
        ]);
    }
}
