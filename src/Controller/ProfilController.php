<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Session;
use App\Form\EvenementType;
use App\Form\SessionType;
use App\Repository\EvenementRepository;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
  /*  #[Route('/profil', name: 'app_profil')]
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
    }*/
/*    #[Route('/{id}/edit', name: 'app_profil-evenement-edit', methods: ['GET', 'POST'])]
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
    }*/


/*#[Route('/new', name: 'app_profil-addevenement', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);
            $id = $evenement->getId();
            return $this->redirectToRoute('app_profil-evenement-session-add', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/addEvenement.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);

    }*/

//#[Route('/new', name: 'app_profil-addevenement', methods: ['GET', 'POST'])]
//    public function new(Request $request, EvenementRepository $evenementRepository): Response
//    {
//        $evenement = new Evenement();
//        $form = $this->createForm(EvenementType::class, $evenement);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $evenementRepository->save($evenement, true);
//            $id = $evenement->getId();
//            return $this->redirectToRoute('app_profil-evenement-session-add', ['id' => $id], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('profil/addEvenement.html.twig', [
//            'evenement' => $evenement,
//            'form' => $form,
//        ]);
//
//    }
/*    #[Route('/{id}', name: 'app_profil-evenement-delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_profil-evenement', [], Response::HTTP_SEE_OTHER);
    }*/


   /* #[Route('/{id}/new', name: 'app_profil-evenement-session-add', methods: ['GET', 'POST'])]
    public function newSession(Request $request,Evenement $evenement, SessionRepository $sessionRepository): Response
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->setEvenement($evenement);
            $sessionRepository->save($session, true);

            return $this->redirectToRoute('app_profil-evenement', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/addSession.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }*/




}
