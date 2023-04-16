<?php

namespace App\Controller;

use App\Entity\PaymentS;
use App\Form\PaymentS2Type;
use App\Form\PaymentSType;
use App\Repository\PaymentSRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment/s')]
class PaymentSController extends AbstractController
{
    #[Route('/', name: 'app_payment_s_index', methods: ['GET'])]
    public function index(PaymentSRepository $paymentSRepository): Response
    {
        return $this->render('payment_s/index.html.twig', [
            'payments' => $paymentSRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_payment_s_new_panierproduit', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentSRepository $paymentSRepository): Response
    {
        $payment = new PaymentS();
        $form = $this->createForm(PaymentSType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentSRepository->save($payment, true);

            return $this->redirectToRoute('app_payment_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_s/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_s_show', methods: ['GET'])]
    public function show(PaymentS $payment): Response
    {
        return $this->render('payment_s/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_s_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentS $payment, PaymentSRepository $paymentSRepository): Response
    {
        $form = $this->createForm(PaymentS2Type::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentSRepository->save($payment, true);

            return $this->redirectToRoute('app_payment_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_s/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_s_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentS $payment, PaymentSRepository $paymentSRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $paymentSRepository->remove($payment, true);
        }

        return $this->redirectToRoute('app_payment_s_index', [], Response::HTTP_SEE_OTHER);
    }
}
