<?php

namespace App\Controller;

use App\Entity\Panierproduit;
use App\Entity\Produit;
use App\Form\PanierproduitType;
use App\Repository\PanierproduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


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


    //remove produit
    #[Route('/remove/{id}', name: 'app_panier_produit_remove', methods: ['GET'])]
    public function remove(Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        $panierproduitRepository->remove($panierproduit, true);
        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    //update quantity produit in panier produit
    /*#[Route('/update/{id}', name: 'minus', methods: ['GET'])]
    public function updateminus(Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        $quantity = $panierproduit->getQuantity();
        $produit = $panierproduit->getIdProduit();

        if ($quantity < $produit->getQt()) {
            $quantity--;
            $panierproduit->setQuantity($quantity);
            $panierproduitRepository->save($panierproduit, true);
        }

        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }
  #[Route('/update/{id}', name: 'plus', methods: ['GET'])]

    public function updateplus(Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        $quantity = $panierproduit->getQuantity();
        $produit = $panierproduit->getIdProduit();

        if ($quantity < $produit->getQt()) {
            $quantity++;
            $panierproduit->setQuantity($quantity);
            $panierproduitRepository->save($panierproduit, true);
        }

        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }*/


    #[Route('/update/{id}', name: 'app_panierproduit_updateminus', methods: ['GET'])]
    public function updateminus(Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        $quantity = $panierproduit->getQuantity();
        if ($quantity >= 1) {
            $quantity--;
            $panierproduit->setQuantity($quantity);
            $panierproduitRepository->save($panierproduit, true);
            if ($quantity == 0) {
                $panierproduitRepository->remove($panierproduit, true);
            }
        }
        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/updateplus/{id}', name: 'app_panier_produit_updateplus', methods: ['GET'])]
    public function updateplus(Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        $quantity = $panierproduit->getQuantity();
        $maxQuantity = $panierproduit->getIdProduit()->getQt();
        if ($quantity < $maxQuantity) {
            $quantity++;
            $panierproduit->setQuantity($quantity);
            $panierproduitRepository->save($panierproduit, true);
        } else {
           echo "la quantité maximale est atteinte";
        }
        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }



//creat functio to navigate to payment.html.twig



/*    #[Route('/paymentpanier', name: 'app_panier_produit_payment', methods: ['GET'])]
    public function paymentPanier(PanierproduitRepository $panierproduitRepository): Response
    {
        $panierproduits = $panierproduitRepository->findAll();
        $total = 0;
        foreach ($panierproduits as $panierproduit) {
            $total += $panierproduit->getIdProduit()->getPrix() * $panierproduit->getQuantity();
        }

        return $this->render('panier_produit/payment.html.twig', [
            'panierproduits' => $panierproduits,
            'total' => $total,
        ]);
    }*/
   #[Route('/', name: 'app_payment',methods: ['GET'])]
    public function payment(Panierproduit $panierproduit): Response
    {
        return $this->render('panier_produit/payment.html.twig.html.twig', [
        ]);
    }




    #[Route('/produitshow/{id}', name: 'produit_image_show')]
    public function showproduitphoto(Produit $produt): Response
    {
        $photo = stream_get_contents($produt->getImage());

        return new Response($photo, 200, ['Content-Type' => 'image/jpeg']);
    }


}
