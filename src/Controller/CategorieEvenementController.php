<?php

namespace App\Controller;

use App\Entity\CategorieEvenement;
use App\Form\CategorieEvenementType;
use App\Repository\CategorieEvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/evenement')]
class CategorieEvenementController extends AbstractController
{
    #[Route('/', name: 'app_categorie_evenement_index', methods: ['GET'])]
    public function index(CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        return $this->render('categorie_evenement/index.html.twig', [
            'categorie_evenements' => $categorieEvenementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        $categorieEvenement = new CategorieEvenement();
        $form = $this->createForm(CategorieEvenementType::class, $categorieEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieEvenementRepository->save($categorieEvenement, true);

            return $this->redirectToRoute('app_categorie_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_evenement/new.html.twig', [
            'categorie_evenement' => $categorieEvenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_evenement_show', methods: ['GET'])]
    public function show(CategorieEvenement $categorieEvenement): Response
    {
        return $this->render('categorie_evenement/show.html.twig', [
            'categorie_evenement' => $categorieEvenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieEvenement $categorieEvenement, CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        $form = $this->createForm(CategorieEvenementType::class, $categorieEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieEvenementRepository->save($categorieEvenement, true);

            return $this->redirectToRoute('app_categorie_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_evenement/edit.html.twig', [
            'categorie_evenement' => $categorieEvenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieEvenement $categorieEvenement, CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieEvenement->getId(), $request->request->get('_token'))) {
            $categorieEvenementRepository->remove($categorieEvenement, true);
        }

        return $this->redirectToRoute('app_categorie_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
