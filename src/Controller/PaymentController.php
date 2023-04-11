<?php

namespace App\Controller;

use App\Form\PaymentFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        return $this->render('panier_produit/payment.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
    #[Route('/payment', name: 'app_payment')]
    public function payment(Request $request): Response
    {
        $form = $this->createForm(PaymentFormType::class);

        return $this->render('panier_produit/payment.html.twig', [
            'form' => $form->createView(),
        ]);


    }

}
