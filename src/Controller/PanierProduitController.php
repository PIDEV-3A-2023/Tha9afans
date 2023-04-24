<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Panierproduit;
use App\Entity\Produit;
use App\Form\PanierproduitType;
use App\Repository\PanierproduitRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;


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
        $prixtotale = 0;
        $paniersproduits = $panierproduitRepository->findPanierByUser($this->getUser());

        foreach ($paniersproduits as $panierproduit) {
            $quantite = $panierproduit->getQuantity();
            $prix = $panierproduit->getIdProduit()->getPrix();
            $prixtotale += $quantite * $prix;
        }
        $this->get('session')->set('prixtotale', $prixtotale);

        return $this->render('panier_produit/index.html.twig', [
            'panierproduits' => $paniersproduits,
            'prixtotale' => $prixtotale
        ]);

    }

    /*#[Route('/new', name: 'app_panier_produit_new', methods: ['GET', 'POST'])]
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
    }*/

    #[Route('/{id}', name: 'app_panier_produit_show', methods: ['GET'])]
    public function show(Panierproduit $panierproduit): Response
    {
        return $this->render('panier_produit/show.html.twig', [
            'panierproduit' => $panierproduit,
        ]);
    }

   /* #[Route('/{id}/edit', name: 'app_panier_produit_edit', methods: ['GET', 'POST'])]
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
    }*/
    /*#[Route('/{id}', name: 'app_panier_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierproduit->getId(), $request->request->get('_token'))) {
            $panierproduitRepository->remove($panierproduit, true);
        }
        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }*/




    //remove produit
    #[Route('/remove/{id}', name: 'app_panier_produit_remove', methods: ['GET'])]
    public function remove(Panierproduit $panierproduit, PanierproduitRepository $panierproduitRepository): Response
    {
        $panierproduitRepository->remove($panierproduit, true);
        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }



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
        $newQuantity = $quantity + 1;
        if ($newQuantity <= $maxQuantity) {
            $panierproduit->setQuantity($newQuantity);
            $panierproduitRepository->save($panierproduit, true);
        } else {
            $this->addFlash('error', 'La quantité maximale est atteinte');
        }
        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }




    //get produit image
    #[Route('/produitshow/{id}', name: 'produit_image_show')]
    public function showproduitphoto(Produit $produt): Response
    {
        $photo = stream_get_contents($produt->getImage());

        return new Response($photo, 200, ['Content-Type' => 'image/jpeg']);
    }






    //clacluler




//checkout stripe service

    private $manager;

    private $gateway;


    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager=$manager;

        $this->gateway= new StripeClient($_ENV['STRIPE_SECRETKEY']);
    }

    #[Route('/checkout', name: 'app_checkout', methods:"POST")]
    public function checkout(Request $request): Response
    {
        $storedtotale=$this->get('session')->get('prixtotale');
        $amount=$storedtotale*100;
        //créer le checkout
        $checkout=$this->gateway->checkout->sessions->create(
            [
                'line_items'=>[[
                    'price_data'=>[
                        'currency'=>$_ENV['STRIPE_CURRENCY'],
                        'unit_amount'=>intval($amount),
                        'product_data'=>[
                            'name'=>'ff',
                            'description'=>'ff',
                            'images'=>["https://images.unsplash.com/photo-1612837017391-4b6b7b0b0b0b?ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8bmlrZXxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&w=1000&q=80"],
                        ],
                    ],
                    'quantity'=>1
                ]],
                'mode'=>'payment',
                'success_url'=>'https://127.0.0.1:8000/success?id_sessions={CHECKOUT_SESSION_ID}',
                'cancel_url'=>'https://127.0.0.1:8000/cancel?id_sessions={CHECKOUT_SESSION_ID}'
            ]);
        return $this->redirect($checkout->url);
    }
    #[Route('/success', name: 'app_success', methods: ['GET'])]
    public function success(Request $request,PanierproduitRepository $panierproduitRepository): Response
    {
        $id_sessions=$request->query->get('id_sessions');

        //Récupère le customer via l'id de la  session
        $customer=$this->gateway->checkout->sessions->retrieve(
            $id_sessions,
            []
        );
        return $this->render('success/success.html.twig',[

        ]);

    }


    #[Route('/cancel', name: 'app_cancel')]
    public function cancel(Request $request): Response
    {
        dd("cancel");
    }

}