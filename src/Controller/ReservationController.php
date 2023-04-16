<?php

namespace App\Controller;

use App\Entity\Billet;
use App\Entity\BilletReserver;
use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\BilletRepository;
use App\Repository\BilletReserverRepository;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/index', name: 'app_reservation_index', methods: ['GET'])]
    public function index($eventId, EvenementRepository $eventRepository): Response
    {
        $event = $eventRepository->find($eventId);
        return $this->render('reservation/index.html.twig', [
            'event' => $event
        ]);
    }


    #[Route('/{reservationId}/{event}/participate/ticket', name: 'app_ticket', methods: ['GET', 'POST'])]
    // function that retrieve the reservation and returns the ticket page
    public function ticket(Request $request,$reservationId,$event,EvenementRepository $evenementRepository, ReservationRepository $reservationRepository,BilletRepository $billetRepository, BilletReserverRepository $billetReserverRepository): Response
    {
        $listBillets = $billetRepository->findBy(['evenement' => $event]);
        $billetReserverNormal= new BilletReserver();
        $billetReserverNormal->setBillet($billetRepository->find($listBillets[0]));
        $billetReserverNormal->setReservation($reservationRepository->find($reservationId));
        $billetReserverNormal->setNombre(0);
        $billetReserverVip= new BilletReserver();
        $billetReserverVip->setBillet($billetRepository->find($listBillets[1]));
        $billetReserverVip->setReservation($reservationRepository->find($reservationId));
        $billetReserverVip->setNombre(0);
        $billetReserverEtudiant= new BilletReserver();
        $billetReserverEtudiant->setBillet($billetRepository->find($listBillets[2]));
        $billetReserverEtudiant->setReservation($reservationRepository->find($reservationId));
        $billetReserverEtudiant->setNombre(0);
        $reservation = $reservationRepository->find($reservationId);
        $billetReserver = $billetReserverRepository->findBy(['reservation' => $reservationId]);
        return $this->render('reservation/next.html.twig', [
            'reservation' => $reservation,
            'billetReserver' => $billetReserver,
            'billets' =>$listBillets,
        ]);
    }
    #[Route('/{eventId}/participate', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(BilletRepository $billetRepository, $eventId,EvenementRepository $eventRepository, Request $request, ReservationRepository $reservationRepository): Response
    {
        $billet= $billetRepository->findBy(['evenement' => $eventId]);
        $reservation = new Reservation();
        $event = $eventRepository->find($eventId);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);
            $id = $reservationRepository->find($reservation)->getId();
            return $this->redirectToRoute('app_ticket', ['reservationId'=>$id ,'event'=>$event->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('reservation/new.html.twig', [
            'billets' => $billet,
            'event' => $event,
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/update/{id}', name: 'app_panierproduit_updateminus', methods: ['GET'])]
    public function updateminus(BilletReserver $billetReserver, BilletReserverRepository $billetReserverRepository): Response
    {
        $quantity = $billetReserver->getNombre();
        if ($quantity >= 1) {
            $quantity--;
            $billetReserver->setNombre($quantity);
            $billetReserverRepository->save($billetReserver, true);
            if ($quantity == 0) {
                $billetReserverRepository->remove($billetReserver, true);
            }
        }
        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/updateplus/{id}', name: 'app_panier_produit_updateplus', methods: ['GET'])]
    public function updateplus(BilletReserver $billetReserver, BilletReserverRepository $billetReserverRepository): Response
    {
        $quantity = $billetReserver->getNombre();
        $maxQuantity = $billetReserver->getReservation()->getQt(); //here
        $newQuantity = $quantity + 1;
        if ($newQuantity <= $maxQuantity) {
            $billetReserver->setNombre($newQuantity);
            $billetReserverRepository->save($billetReserver, true);
        } else {
            $this->addFlash('error', 'La quantité maximale est atteinte');
        }
        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
