<?php

namespace App\Controller;

use App\Form\PaymentStripeType;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentStripeController extends AbstractController
{

    /**
     * @throws ApiErrorException
     */
    #[Route('/paymentS', name: 'paymentS')]
    public function payment(Request $request): Response
    {
        $form = $this->createForm(PaymentStripeType::class);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            // Set the Stripe API key
            Stripe::setApiKey('sk_test_51MVLflEX2tyYf7mRCjmMejBib6wYBEfM7Gez7f2XlkpHKOMegvWtGkPXFGowobqYLTf5ZmUbOmRQc3XLPsLJyGsK00k6TIuVCO
');

            $token = $form->get('stripeToken')->getData();
            $charge = Charge::create([
                'amount' => 1000,
                'currency' => 'usd',
                'description' => 'Example charge',
                'source' => $token,
            ]);

            // handle successful payment
        }
        return $this->render('payment_stripe/payment.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
