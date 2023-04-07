<?php

namespace App\Controller;

use App\Entity\Panierproduit;
use App\Form\PanierproduitType;
use App\Repository\PanierproduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panierproduit')]
class PanierProduitController extends AbstractController
{
    #[Route('/', name: 'app_panier_produit_index', methods: ['GET'])]
    public function index(PanierproduitRepository $panierproduitRepository): Response
    {
        return $this->render('panier_produit/index.html.twig', [
            'panierproduits' => $panierproduitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_panier_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PanierproduitRepository $panierproduitRepository): Response
    {
        $panierproduit = new Panierproduit();
        $form = $this->createForm(PanierproduitType::class, $panierproduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panierproduitRepository->save($panierproduit, true);

            return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier_produit/new.html.twig', [
            'panierproduit' => $panierproduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_produit_show', methods: ['GET'])]
    public function show(Panierproduit $panierproduit): Response
    {
        return $this->render('panier_produit/show.html.twig', [
            'panierproduit' => $panierproduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        $form = $this->createForm(PanierproduitType::class, $panierproduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panierproduitRepository->save($panierproduit, true);

            return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier_produit/edit.html.twig', [
            'panierproduit' => $panierproduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierproduit->getId(), $request->request->get('_token'))) {
            $panierproduitRepository->remove($panierproduit, true);
        }

        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    //get produit image

    #[Route('/produitimageshow/{id}', name: 'produitimageshow', methods: ['GET'])]
    public function showphoto(Panierproduit $panierproduit): Response
    {
        $photo = stream_get_contents($panierproduit->getIdProduit()->getImage());

        return new Response($photo, 200, ['Content-Type' => 'image/jpeg']);
    }

}
