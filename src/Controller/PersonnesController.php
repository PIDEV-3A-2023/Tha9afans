<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PersonnesRepository;

class PersonnesController extends AbstractController
{
    #[Route('/personnes', name: 'app_personnes')]
    public function index(): Response
    {
        return $this->render('personnes/index.html.twig', [
            'controller_name' => 'PersonnesController',
        ]);
    }

    #[Route('/testpersonne',name:'Aff')]
    function Affiche(PersonnesRepository $repo){
        $personne=$repo->findAll();
        return $this->render(
            'personnes/affiche.html.twig',['pp'=>$personne]
        );

    }
}
