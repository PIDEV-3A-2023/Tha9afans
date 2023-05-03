<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{


    #[Route('/{id}/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentaireRepository $commentaireRepository, Evenement $evenement): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        $user = $this->getUser();
        $dateTime = new \DateTime();
        $currentDateTime = $dateTime->format('Y-m-d H:i:s');
        $dateTimeObject = \DateTime::createFromFormat('Y-m-d H:i:s', $currentDateTime);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setUser($user);
            $commentaire->setEvenement($evenement);
            $commentaire->setDate($dateTimeObject);
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {$evenement = $commentaire->getEvenement();
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }

        return $this->redirectToRoute('app_evenement_show', ['id'=>$evenement->getId()], Response::HTTP_SEE_OTHER);
    }
}
