<?php

namespace App\Controller;

use App\Entity\BilletReserver;
use App\Entity\Reservation;
use App\Form\ReservationEditType;
use App\Form\ReservationType;
use App\Repository\BilletRepository;
use App\Repository\BilletReserverRepository;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/index/', name: 'app_reservation_index', methods: ['GET'])]
    public function index($eventId, EvenementRepository $eventRepository): Response
    {
        $event = $eventRepository->find($eventId);
        return $this->render('reservation/index.html.twig', [
            'event' => $event
        ]);
    }

    #[Route('/{reservationId}/{eventId}/participate/ticket', name: 'app_ticket', methods: ['GET', 'POST'])]
    public function ticket(Request $request, $reservationId, $eventId, EvenementRepository $evenementRepository, ReservationRepository $reservationRepository, BilletRepository $billetRepository, BilletReserverRepository $billetReserverRepository): Response
    {
        $reservation = $reservationRepository->find($reservationId);
        $billets = $billetRepository->findBy(['evenement' => $eventId]);

        // Initialize an empty array to hold the BilletReserver objects
        $billetReserversByType = [];
        $qrCodeTable = [];
        //  foreach billet store the qrCideDataUri in a table named qrCodeTable and render it in the view
        foreach ($billets as $billet) {
            $billetCode = "CODE".$billet->getType().$billet->getEvenement()->getNom().$billet->getEvenement()->getcreateur()->getId().$reservation->getNom();
            $renderer = new Png();
            $renderer->setWidth(250);
            $renderer->setHeight(250);
            $writer = new Writer($renderer);
            $qrCode = $writer->writeString($billetCode);
            $qrCodeDataUri = "data:image/png;base64," . base64_encode($qrCode);
            $qrCodeTable[] = $qrCodeDataUri;
        }


        if ($request->getMethod() == 'POST') {
            // Retrieve the form data
            $normalCount = $request->request->getInt('Normal');
            $vipCount = $request->request->getInt('VIP');
            $studentCount = $request->request->getInt('Etudiant');
            // Create new BilletReserver objects for the selected ticket types
            foreach ($billets as $billet) {
                $type = $billet->getType();
                $billetReserver = new BilletReserver();
                $billetReserver->setBillet($billet);
                $billetReserver->setReservation($reservation);
                $billetReserver->setNombre(0);


                if ($type == 'Normal') {
                    $billetReserver->setNombre($normalCount);
                } elseif ($type == 'VIP') {
                    $billetReserver->setNombre($vipCount);
                } elseif ($type == 'Etudiant') {
                    $billetReserver->setNombre($studentCount);
                }

                $billetReserversByType[$type] = $billetReserver;
            }

            // Save the new BilletReserver objects to the database
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($billetReserversByType as $billetReserver) {
                $entityManager->persist($billetReserver);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket', ['reservationId' => $reservationId, 'eventId'=>$eventId]);
        }

        return $this->render('reservation/next.html.twig', [
            'reservation' => $reservation,
            'billets'=>$billets,
            'eventId' => $eventId,
            'qrCodeTable' => $qrCodeTable,
        ]);
    }

    #[Route('/{eventId}/participate', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(BilletRepository $billetRepository, $eventId,EvenementRepository $eventRepository, Request $request, ReservationRepository $reservationRepository): Response
    {
        $billet= $billetRepository->findBy(['evenement' => $eventId]);
        $reservation = new Reservation();
        $event = $eventRepository->find($eventId);
        $user= $this->getUser();

        $form = $this->createForm(ReservationType::class, $reservation,[
            'localisation-initial-value' => $event->getLocalisation(),
            'date-initial-value' => $event->getDate(),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($user);
            $reservation->setStatus('en attente');
            $reservation->setPaymentStatus('non payé');
            $reservation->setLocation($event->getLocalisation());
            $reservationRepository->save($reservation, true);
            $id = $reservationRepository->find($reservation)->getId();
            return $this->redirectToRoute('app_ticket', ['reservationId'=>$id ,'eventId'=>$event->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('reservation/new.html.twig', [
            'billets' => $billet,
            'event' => $event,
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(ReservationRepository $reservationRepository): Response
    {
        $user= $this->getUser();
        $reservations = $reservationRepository->findBy(['user' => $user]);
        return $this->render('reservation/show.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {

        $form = $this->createForm(ReservationEditType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);
            return $this->redirectToRoute('app_profil-reservation', [], Response::HTTP_SEE_OTHER);
        }
        /*dump($form->getErrors());*/
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



    #[Route('/update/{id}', name: 'ap_updateminus', methods: ['GET'])]
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



    #[Route('/updateplus/{id}', name: 'app_updateplus', methods: ['GET'])]
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