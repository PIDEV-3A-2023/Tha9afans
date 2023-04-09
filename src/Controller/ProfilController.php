<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
    #[Route('/profil/evenement/', name: 'app_profil-evenement')]
    public function evenement(): Response
    {
        return $this->render('profil/evenement.html.twig');
    }
    #[Route('/profil/Myaccount/', name: 'app_profil-Myaccount')]
    public function Myaccount(): Response
    {
        return $this->render('profil/myAccount.html.twig');
    }
    #[Route('/profil/facture/', name: 'app_profil-Myaccount')]
    public function facture(): Response
    {
        return $this->render('profil/facture.html.twig');
    }


}
