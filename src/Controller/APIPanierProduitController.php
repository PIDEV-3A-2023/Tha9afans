<?php

namespace App\Controller;

use App\Entity\Panierproduit;
use App\Repository\PanierproduitRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;;

class APIPanierProduitController extends AbstractController
{
    #[Route('/apiproduit', name: 'app_a_p_i_panier_produit', methods: ['GET'])]
    public function index(Request $request, PanierproduitRepository $panierproduitRepository, PanierRepository $panierRepository): JsonResponse
    {
        $prixtotale = 0;
        $panier = $panierRepository->findAll();
        $paniersproduits = $panierproduitRepository->findAll();

        $sort = $request->query->get('sort');
        if ($sort == 'DESC') {
            usort($paniersproduits, function($a, $b) {
                return $b->getIdProduit()->getPrix() <=> $a->getIdProduit()->getPrix();
            });
        } else {
            usort($paniersproduits, function($a, $b) {
                return $a->getIdProduit()->getPrix() <=> $b->getIdProduit()->getPrix();
            });
        }

        $panierproduitsData = []; // Initialize the variable

        foreach ($paniersproduits as $panierproduit) {
            $quantite = $panierproduit->getQuantity();
            $prix = $panierproduit->getIdProduit()->getPrix();
            $prixtotale += $quantite * $prix;

            $this->get('session')->set('prixtotale', $prixtotale);
            $this->get('session')->set('', $paniersproduits);

            $produit = $panierproduit->getIdProduit();
            $image = null;
            if ($produit->getImage() !== null) {
                $image = base64_encode(stream_get_contents($produit->getImage()));
            }
            $panierproduitsData[] = [
                'id' => $panierproduit->getId(),
                'produit_nom' => $panierproduit->getIdProduit()->getNom(),
                'produit_id' => $panierproduit->getIdProduit()->getId(),
                'quantite' => $panierproduit->getQuantity(),
                'prix_unitaire' => $produit->getPrix(),
                //'image' => $image,
                'prix_total' => $produit->getPrix() * $panierproduit->getQuantity(),
                'panier_id' => $panierproduit->getIdPanier()->getId(),
                

            ];
        }

        $data = [
            'panierproduits' => $panierproduitsData,
        ];

        return new JsonResponse($data);
    }

}
