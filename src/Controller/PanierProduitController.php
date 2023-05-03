<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Commandeproduit;

use App\Entity\Panierproduit;
use App\Entity\Produit;

use App\Repository\CommandeproduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\PanierproduitRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;

use Exception;
use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;









#[Route('/panierproduit')]
class PanierProduitController extends AbstractController
{

    #[Route('/', name: 'app_panier_produit_index', methods: ['GET'])]
    public function index(Request $request ,PanierproduitRepository $panierproduitRepository , PanierRepository $panierRepository): Response
    {
        $prixtotale = 0;
        $panier = $panierRepository->findPanierByUser($this->getUser());

        $paniersproduits = $panierproduitRepository->findBy(['idPanier' => $panier]);

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

        foreach ($paniersproduits as $panierproduit) {
            $quantite = $panierproduit->getQuantity();
            $prix = $panierproduit->getIdProduit()->getPrix();
            $prixtotale += $quantite * $prix;
        }

        $this->get('session')->set('prixtotale', $prixtotale);
        $this->get('session')->set('', $paniersproduits);

        return $this->render('panier_produit/index.html.twig', [
            'panierproduits' => $paniersproduits,
            'prixtotale' => $prixtotale,
            'sort' => $sort,
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










//checkout stripe service

    private $manager;
    private $gateway;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager=$manager;

        $this->gateway= new StripeClient($_ENV['STRIPE_SECRETKEY']);
    }

    #[Route('/checkout', name: 'app_checkout', methods:"POST")]
    public function checkout(Request $request, PanierproduitRepository $panierproduitRepository, PanierRepository $panierRepository): Response
    {
        // récupérer le code de réduction
        $discountCode = $request->request->get('discount_code');

        // récupérer les produits du panier
        $panier = $panierRepository->findPanierByUser($this->getUser());
        $paniersproduits = $panierproduitRepository->findBy(['idPanier' => $panier]);



        $line_items = [];
        foreach ($paniersproduits as $panierproduit) {
            $quantite = $panierproduit->getQuantity();
            $prix = $panierproduit->getIdProduit()->getPrix();
            $nom_produit = $panierproduit->getIdProduit()->getNom();
            $description_produit = $panierproduit->getIdProduit()->getDescription();

            // apply discount if discount code is valid
            if ($discountCode === 'tha9afans' || $discountCode === 'marwen' || $discountCode === 'ons') {
                $discounted_price = $prix * 0.2;
            } else {
                $discounted_price = $prix;
            }

            // check if total price is zero
            if ($discounted_price == 0) {
                // afficher le message d'erreur et rediriger vers la page de panier
                $this->addFlash('error', 'Vous ne pouvez pas payer car le prix total est de 0.');
                return $this->redirectToRoute('app_panier_produit_index');
            }
            

            $line_items[] = [
                'price_data'=>[
                    'currency'=>$_ENV['STRIPE_CURRENCY'],
                    'unit_amount' => intval($discounted_price * 100),
                    'product_data'=>[
                        'name'=>$nom_produit,
                        'description'=>$description_produit,
                    ],
                ],
                'quantity'=>$quantite,
            ];
        }


        // créer le checkout
        $checkout = $this->gateway->checkout->sessions->create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1:8000/success?id_sessions={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://127.0.0.1:8000/cancel?id_sessions={CHECKOUT_SESSION_ID}'
        ]);

        return $this->redirect($checkout->url);
    }

    /**
     * @throws ApiErrorException
     * @throws TypeException
     */
    #[Route('/success', name: 'app_success', methods: ['GET'])]
    public function success(Request $request,PanierproduitRepository $panierproduitRepository , CommandeRepository $commandeRepository , PanierRepository $panierRepository,CommandeproduitRepository $commandeproduitRepository): Response
    {
        $id_sessions = $request->query->get('id_sessions');
        //Récupère le customer via l'id de la  session
        $customer = $this->gateway->checkout->sessions->retrieve(
            $id_sessions,
            []
        );
        //Récupérer les informations du customer et de la transaction
        $name = $customer["customer_details"]["name"];
        $email = $customer["customer_details"]["email"];
        $payment_status = $customer["payment_status"];
        $amount = $customer['amount_total'];

        // Create a new instance of the Commande entity
        $commande = new Commande();
        // Set the properties of the Commande entity
        $commande->setDatecommande(new \DateTime());
        $commande->setTotal($amount);

        $commande->setEtat("1");
        $commande->setIdUser($this->getUser()); // assuming that you are using Symfony's security component


        // Persist the Commande entity to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();

        //returne the commande id
        $commandeid = $commandeRepository->findBy([
            'datecommande' => $commande->getDatecommande(),
            'total' => $commande->getTotal(),
            /*'id_user' => $commande->getIdUser()->getId(),*/
        ]);

        $panier = $panierRepository->findPanierByUser($this->getUser());
        $paniersproduits = $panierproduitRepository->findBy(['idPanier' => $panier]);

        foreach ($paniersproduits as $panierproduit) {
            $commandeproduit = new Commandeproduit();
            $commandeproduit->setIdCommende($commandeid[0]);
            $commandeproduit->setQuantite($panierproduit->getQuantity());
            $commandeproduit->setIdProduit($panierproduit->getIdProduit()->getId());
            $commandeproduitRepository->save($commandeproduit, true);

        }
       //send email to the customer after success payment using MailerService  $name = $customer["customer_details"]["name"];

        //remove the panierproduit after success
        $panier = $panierRepository->findPanierByUser($this->getUser());
        $paniersproduits = $panierproduitRepository->findBy(['idPanier' => $panier]);
        foreach($paniersproduits as $panierproduit){
            $panierproduitRepository->remove($panierproduit, true);
        }



        return $this->render('success/success.html.twig',[

        ]);

    }


    #[Route('/cancel', name: 'app_cancel')]
    public function cancel(Request $request): Response
    {
        $this->addFlash('warning', 'You have cancelled the payment.');
        return $this->redirectToRoute('app_panier_produit_index');
    }






}