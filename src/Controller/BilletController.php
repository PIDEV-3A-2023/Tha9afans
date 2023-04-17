<?php

namespace App\Controller;

use App\Entity\Billet;
use App\Form\BilletType;
use App\Repository\BilletRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/billet')]
class BilletController extends AbstractController
{
    #[Route('/', name: 'app_billet_index', methods: ['GET'])]
    public function index(BilletRepository $billetRepository): Response
    {
        return $this->render('billet/index.html.twig', [
            'billets' => $billetRepository->findAll(),

        ]);
    }

    #[Route('/new/{eventId}', name: 'app_billet_new', methods: ['GET', 'POST'])]
    public function new(EvenementRepository $evenementRepository,$eventId ,Request $request, BilletRepository $billetRepository): Response
    {
        $event= $evenementRepository->find($eventId);
        $billet = new Billet();
        if (!$event->getFreeorpaid()) {
            $prixInitialValue=0;
        } else {
            $prixInitialValue = null;
        }
        $form = $this->createForm(BilletType::class, $billet,[
            'code_initial_value'=>"CODE".$billet->getType().$event->getId().$event->getNom().$event->getcreateur()->getId(),
            'date_initial_value'=>$event->getDate(),
            'prix_initial_value'=>$prixInitialValue,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // verify if the type of billet is already exist, so you can't add it again
            $billetType = $billetRepository->findOneBy(['type' => $billet->getType(), 'evenement' => $eventId]);
            if ($billetType) {
                //add a pop up message to inform the user that the type of billet is already exist
                $this->addFlash('warning', 'Ce type de billet existe déjà');
                return $this->redirectToRoute('app_billet_new', ['eventId'=>$eventId], Response::HTTP_SEE_OTHER);
            }
            $billet->setEvenement($event);
            $billetRepository->save($billet, true);
            $this->addFlash('success', 'Le billet a été ajouté avec succès.');

            return $this->redirectToRoute('app_billet_new', ['eventId'=>$eventId], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('billet/new.html.twig', [
            'billets' => $billetRepository->findBy(['evenement' => $eventId]),
            'billet' => $billet,
            'form' => $form,
            'event' => $event,
        ]);
    }

    #[Route('/{id}/show', name: 'app_billet_show', methods: ['GET'])]
    public function show(Billet $billet ): Response
    {
        return $this->render('billet/billetShow.html.twig', [
            'billet' => $billet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_billet_edit', methods: ['GET','POST'])]
    public function edit($id,Request $request, Billet $billet, BilletRepository $billetRepository): Response
    {
        $form = $this->createFormBuilder($billet)
            ->add('prix', NumberType::class, [
                'label' => 'Prix'
            ])
            ->add('nbrBilletAvailable', NumberType::class, [
                'label' => 'Nombre de billets disponibles'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer les modifications',
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $billetRepository->save($billet, true);

            return $this->redirectToRoute('app_billet_new', ['eventId'=>$billet->getEvenement()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('billet/edit.html.twig', [
            'billet' => $billet,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app-billet-delete', methods: ['POST'])]
    public function delete(Request $request, Billet $billet, BilletRepository $billetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billet->getId(), $request->request->get('_token'))) {
            $billetRepository->remove($billet, true);
        }

        return $this->redirectToRoute('app_billet_new', ['eventId'=> $billet->getEvenement()->getId()], Response::HTTP_SEE_OTHER);
    }
}
