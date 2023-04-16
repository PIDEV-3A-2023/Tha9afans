<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\GalerieRepository;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{

    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    /* #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
     public function new(Request $request, EvenementRepository $evenementRepository): Response
     {
         $evenement = new Evenement();
         $form = $this->createForm(EvenementType::class, $evenement);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $evenementRepository->save($evenement, true);

             return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
         }

         return $this->renderForm('evenement/new.html.twig', [
             'evenement' => $evenement,
             'form' => $form,
         ]);
     }*/
    #[Route('/new', name: 'app_profil-addevenement', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($evenementRepository->findOneBy(['nom' => $evenement->getNom(),'date'=>$evenement->getDate(),'description'=>$evenement->getDescription()])){
                $this->addFlash('danger', 'Evenement deja existe!');
                return $this->redirectToRoute('app_profil-addevenement', [], Response::HTTP_SEE_OTHER);
            }
            $evenementRepository->save($evenement, true);
            $id = $evenement->getId();

            return $this->redirectToRoute('app_profil-evenement-session-add', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/addEvenement.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);

    }
    #[Route('/{id}/edit', name: 'app-evenement-edit', methods: ['GET', 'POST'])]
    public function editEvenement(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_profil-evenement-session', ['id'=>$evenement->getId()], Response::HTTP_SEE_OTHER  );
        }

        return $this->renderForm('profil/editEvenement.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement,SessionRepository $sessionRepository,GalerieRepository $galerieRepository): Response
    { $sessions = $sessionRepository->findBy(['evenement' => $evenement]);
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'sessions' => $sessions,
        ]);
    }

    /*  #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
      public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
      {
          $form = $this->createForm(EvenementType::class, $evenement);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $evenementRepository->save($evenement, true);

              return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
          }

          return $this->renderForm('evenement/editSession.html.twig', [
              'evenement' => $evenement,
              'form' => $form,
          ]);
      }*/

    /* #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
     public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
     {
         if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
             $evenementRepository->remove($evenement, true);
         }

         return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
     }*/
    #[Route('/{id}', name: 'app-evenement-delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_profil-evenement', [], Response::HTTP_SEE_OTHER);
    }
}
