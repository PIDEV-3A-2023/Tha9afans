<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/profil/Myaccount/', name: 'app_profil-Myaccount')]
    public function Myaccount(): Response
    {
        return $this->render('profil/myAccount.html.twig');
    }

    #[Route('/profil/facture/', name: 'app_profil-facture')]
    public function facture(): Response
    {
        return $this->render('profil/facture.html.twig');
    }



    #[Route('/profil/evenement/', name: 'app_profil-evenement')]
    public function evenement(EvenementRepository $evenementRepository): Response
    {
        return $this->render('profil/evenement.html.twig',[
            'evenements' => $evenementRepository->findAll(),
        ]);
    }
    #[Route('/{id}/edit', name: 'app_profil-evenement-edit', methods: ['GET', 'POST'])]
    public function editEvenement(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_profil-evenement', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/editEvenement.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

}
