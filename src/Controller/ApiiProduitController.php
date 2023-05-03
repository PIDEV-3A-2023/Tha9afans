<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiiProduitController extends AbstractController
{
    public function allProduitAction()
    {
        $produits = $this->getDoctrine()->getManager()->getRepository(Produit::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);
    }

    #[Route('/api/produits/deleteproduit', name: 'api_produits_index', methods: ['GET'])]
    public function deleteProduitAction(Request $request)
    {
        $id=$request->get("id");

        $produit = $this->getDoctrine()->getManager()->getRepository(Produit::class)->find($id);
        if ($produit!=null) {
            $em->remove($produit);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize("Produit has been deleted succefully");
        }


        return new JsonResponse("produit is unvailable");
    }

    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function addProduitAction(Request $request)
    {

        $produit = new Produit();
        $nom = $request->query->get('nom');
        $description = $request->query->get('description');
        $prix = floatval($request->query->get('prix'));
        $remise = floatval($request->query->get('remise'));
        $rating = floatval($request->query->get('rating'));
        $prixapresremise = floatval($request->query->get('prixapresremise'));
        $libelle = intval($request->query->get('libelle'));
        $qt = intval($request->query->get('qt'));
        $produit->setNom($data['nom'] ?? null);
        $produit->setDescription($data['description'] ?? null);
        $produit->setLibelle($data['libelle'] ?? null);
        $produit->setPrix($data['prix'] ?? null);

        $produit->setRemise($data['remise'] ?? null);
        $produit->setRating($data['rating'] ?? null);
        $produit->setPrixapresremise($data['prixapresremise'] );
        $produit->setQt($data['qt'] ?? null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse("product has added successfully");
    }
    #[Route('/api/produits/{id}', name: 'api_produits_edit', methods: ['PUT'])]
    public function modifierProduitAction(){
        $em = $this->getDoctrine()->getManager();
        $produit=$this->getDoctrine()->getManager()
            ->getRepository(Produit :: class)
            ->find($request->get('id'));
        $produit->setDescription($request->get("decription"));
        $produit->setLibelle($request->get("libelle"));
        $produit->setPrix($request->get("prix"));
        $produit->setRemise($request->get("remise"));
        $produit->setRating($request->get("rating"));
        $produit->setPrixapresremise($request->get("prixapresremise"));
        $produit->setQt($request->get("qt"));
        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse("product has modified successfully");




}
}