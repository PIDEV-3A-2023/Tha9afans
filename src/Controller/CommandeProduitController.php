<?php

namespace App\Controller;

use App\Entity\Commandeproduit;
use App\Form\CommandeproduitType;
use App\Repository\CommandeproduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande/produit')]
class CommandeProduitController extends AbstractController
{
    #[Route('/', name: 'app_commande_produit_index', methods: ['GET'])]
    public function index(CommandeproduitRepository $commandeproduitRepository): Response
    {
        return $this->render('commande_produit/index.html.twig', [
            'commandeproduits' => $commandeproduitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeproduitRepository $commandeproduitRepository): Response
    {
        $commandeproduit = new Commandeproduit();
        $form = $this->createForm(CommandeproduitType::class, $commandeproduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeproduitRepository->save($commandeproduit, true);

            return $this->redirectToRoute('app_commande_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande_produit/new.html.twig', [
            'commandeproduit' => $commandeproduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_produit_show', methods: ['GET'])]
    public function show(Commandeproduit $commandeproduit): Response
    {
        return $this->render('commande_produit/show.html.twig', [
            'commandeproduit' => $commandeproduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commandeproduit $commandeproduit, CommandeproduitRepository $commandeproduitRepository): Response
    {
        $form = $this->createForm(CommandeproduitType::class, $commandeproduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeproduitRepository->save($commandeproduit, true);

            return $this->redirectToRoute('app_commande_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande_produit/edit.html.twig', [
            'commandeproduit' => $commandeproduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Commandeproduit $commandeproduit, CommandeproduitRepository $commandeproduitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandeproduit->getId(), $request->request->get('_token'))) {
            $commandeproduitRepository->remove($commandeproduit, true);
        }

        return $this->redirectToRoute('app_commande_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
