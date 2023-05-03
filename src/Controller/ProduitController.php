<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Panier;
use App\Entity\Panierproduit;
use App\Entity\Produit;
use App\Form\PanierproduitType;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use App\Repository\EvenementRepository;
use App\Repository\PanierproduitRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository,CategorieRepository $categorieRepository,Request $request): Response
    {
        $categoryId = $request->query->getInt('category');

        if ($categoryId) {
            $produits = $produitRepository->findBy(['idCategorie' => $categoryId]);
        } else {
            $produits = $produitRepository->findAll();
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'categories' => $categorieRepository->findAll(),
            'selectedCategoryId' => $categoryId

        ]);
    }
    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, Produit $produit): Response
    {

        return $this->render('produit/show.html.twig', [
//            'produits' => $produitRepository->findAll(),
//            'categories' => $categorieRepository->findAll(),
            'produit' => $produit,

        ]);
    }

    #[Route('/produit/nouveau', name:'nouveau_produitadd')]
    public function newProduit(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $produit->setIdVendeur($this->getUser()); // Set the ID of the vendor to the user ID
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
            /*$this->addFlash('success', 'The produit has been created successfully.');*/
            return $this->render('produit/new.html.twig', [
                'produits' => $produit,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('produit/new.html.twig', [
            'form' => $form->createView(),


        ]);
    }





    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);

    }
    #[Route('/produitshow/{id}', name: 'produit_show')]
    public function showphoto(Produit $produit): Response
    {
        $image = stream_get_contents($produit->getImage());

        return new Response($image, 200, ['Content-Type' => 'image/jpeg']);
    }


//    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
//    public function show(Produit $produit): Response
//    {
//        return $this->render('produit/show.html.twig', [
//            'produit' => $produit,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route("/produits/search", name: 'app_produit_search', methods: ['POST'])]
    public function search(Request $request, ProduitRepository $produitRepository,CategorieRepository $categorieRepository): Response
    {
        $nom = $request->request->get('nom');

        $produits = $produitRepository->createQueryBuilder('produit')
            ->andWhere('produit.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
            ->getQuery()
            ->getResult();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,

            'categories' => $categorieRepository->findAll(),
            'selectedCategoryId' => null,
        ]);
    }




    #[Route('/{id}', name: 'app-produitsS-delete', methods: ['POST'])]
    public function deleteproduts(Request $request, Produit $produitt, ProduitRepository $produitRepositoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitt->getId(), $request->request->get('_token'))) {
            $produitRepositoryRepository->remove($produitt, true);
        }

        return $this->redirectToRoute('app_profil-produit1', [], Response::HTTP_SEE_OTHER);

    }




    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addToCart(Request $request, Produit $produit, PanierRepository $panierRepository, PanierproduitRepository $panierproduitRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $panier = $panierRepository->findPanierByUser1($user);

        // Check if the user has a cart, if not create one
        if (!$panier) {
            $panier = new Panier();
            $panier->setTotal(0);
            $panier->setIdUser($user);
            $panier->setIspayed(false);
            $entityManager->persist($panier);
            $entityManager->flush();
        }

        $cartItem = new Panierproduit();
        $cartItem->setIdPanier($panier);
        $cartItem->setIdProduit($produit);
        $cartItem->setQuantity($request->request->get('quantity', 1));

        $entityManager->persist($cartItem);
        $entityManager->flush();

        $this->addFlash('success', 'The product has been added to your cart.');
        return $this->redirectToRoute('app_produit_index');
    }










}





