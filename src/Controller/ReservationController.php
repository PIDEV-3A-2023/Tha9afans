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
            $renderer->setWidth(150);
            $renderer->setHeight(150);
            $writer = new Writer($renderer);
            $qrCode = $writer->writeString($billetCode);
            $qrCodeDataUri = "data:image/png;base64," . base64_encode($qrCode);
            $qrCodeTable[] = $qrCodeDataUri;
        }
        if ($request->getMethod() == 'POST') {
            // Retrieve the form data
            //if the number of tickets normalCount is more than the number of tickets available show and error message
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


                if ($type == 'Normal' and $normalCount <= $billet->getNbrBilletAvailable()) {
                    $billetReserver->setNombre($normalCount);
                    $billet->setNbrBilletAvailable($billet->getNbrBilletAvailable() - $normalCount);
                }elseif ($type == 'Normal' and $normalCount > $billet->getNbrBilletAvailable()){
                    $this->addFlash('error', 'Le nombre de billets normaux est supérieur au nombre de billets disponibles');
                    return $this->redirectToRoute('app_ticket', ['reservationId' => $reservationId, 'eventId'=>$eventId]);
                }elseif ($type == 'VIP' and $vipCount > $billet->getNbrBilletAvailable()){
                    $this->addFlash('error', 'Le nombre de billets VIP est supérieur au nombre de billets disponibles');
                    return $this->redirectToRoute('app_ticket', ['reservationId' => $reservationId, 'eventId'=>$eventId]);
                }elseif ($type == 'VIP' and $vipCount <= $billet->getNbrBilletAvailable()) {
                    $billetReserver->setNombre($vipCount);
                    $billet->setNbrBilletAvailable($billet->getNbrBilletAvailable() - $vipCount);
                } elseif ($type == 'Etudiant' and $studentCount <= $billet->getNbrBilletAvailable()) {
                    $billetReserver->setNombre($studentCount);
                }elseif ($type == 'Etudiant' and $studentCount > $billet->getNbrBilletAvailable()){
                    $this->addFlash('error', 'Le nombre de billets Etudiant est supérieur au nombre de billets disponibles');
                    return $this->redirectToRoute('app_ticket', ['reservationId' => $reservationId, 'eventId'=>$eventId]);
                }

                $billetReserversByType[$type] = $billetReserver;
            }

            // Save the new BilletReserver objects to the database
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($billetReserversByType as $billetReserver) {
                $entityManager->persist($billetReserver);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_show', ['id'=>$eventId]);
        }

        return $this->render('reservation/next.html.twig', [
            'reservation' => $reservation,
            'billets'=>$billets,
            'eventId' => $eventId,
            'qrCodeTable' => $qrCodeTable,
        ]);
    }

    #[Route('/{eventId}/participate', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(BilletRepository $billetRepository, $eventId,EvenementRepository $eventRepository, Request $request, ReservationRepository $reservationRepository, BilletReserverRepository $billetReserverRepository): Response
    {
        $billet= $billetRepository->findBy(['evenement' => $eventId]);
        $reservation = new Reservation();
        $event = $eventRepository->find($eventId);
        $user= $this->getUser();

        $existingReservation = $reservationRepository->createQueryBuilder('r')
            ->join('r.billetReservers', 'br')
            ->join('br.billet', 'b')
            ->andWhere('r.user = :user')
            ->andWhere('b.evenement = :evenement')
            ->setParameter('user', $user)
            ->setParameter('evenement', $event)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($existingReservation) {
            $this->addFlash('danger', 'Vous avez déjà une réservation pour cet événement');
            return $this->redirectToRoute('app_evenement_show',  ['id' => $eventId]);
        }

        $form = $this->createForm(ReservationType::class, $reservation,[
            'localisation-initial-value' => $event->getAddresse(),
            'date-initial-value' => $event->getDate(),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($user);
            $reservation->setStatus('en attente');
            $reservation->setPaymentStatus('non payé');
            $reservation->setLocation($event->getAddresse());
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

}