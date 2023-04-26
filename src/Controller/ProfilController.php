<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\EvenementType;
use App\Form\ReservationType;
use App\Repository\BilletRepository;
use App\Repository\BilletReserverRepository;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

class ProfilController extends AbstractController
{
      #[Route('/profil', name: 'app_profil')]
        public function index(): Response
        {
            return $this->render('profil/index.html.twig', [
                'controller_name' => 'ProfilController',
            ]);
        }
        #[Route('/profil/Myaccount/', name: 'app_profil-Myaccount')]
        public function Myaccount(): Response
        {
            return $this->render('profil/myAccount.html.twig');
        }
        #[Route('/profil/facture/', name: 'app_profil-facture')]
        public function facture(): Response
        {
            return $this->render('profil/facture.html.twig');
        }
        #[Route('/profil/reservation/', name: 'app_profil-reservation')]
    public function reservation(ReservationRepository $reservationRepository , BilletReserverRepository $billetReserverRepository): Response
    {
        $user= $this->getUser();
        $reservations = $reservationRepository->findBy(['user' => $user]);
        foreach ($reservations as $reservation) {
            $billetReservers = $billetReserverRepository->findBy(['reservation' => $reservation]);
            $resultatPrixReservation=0;
            $resultatNombreBillet=0;
            $result []=[] ;
            foreach ($billetReservers as $billetReserver) {
               $resultatPrixReservation += $billetReserver->getBillet()->getPrix();
               $resultatNombreBillet += $billetReserver->getNombre();
            }
            $reservation->setTotalPrice($resultatPrixReservation);
            $reservation->setNombreBillet($resultatNombreBillet);
        }

        return $this->render('profil/reservation.html.twig',[
            'reservations' => $reservations
        ]);
    }
    public function downloadPdfAction($reservationId, ReservationRepository $reservationRepository, BilletReserverRepository $billetReserverRepository,BilletRepository $billetRepository)
    {
        // Get the reservation and associated ticket information
        $reservation = $reservationRepository->find($reservationId);
        // get billet from billetReserver
        $billets = $billetReserverRepository->findBy(['reservation' => $reservation]);
        $tickets= $billetRepository->findBy(['id' => $billets]);

        // Render the ticket as HTML
        $html = $this->renderView('profil/ticket.html.twig', [
            'reservation' => $reservation,
            'billets' => $tickets
        ]);

        // Generate the PDF file
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Return the PDF file as a response
        $response = new Response();
        $response->setContent($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="ticket.pdf"');

        return $response;
    }

    #[Route('/profil/evenement/', name: 'app_profil-evenement')]
    public function evenement(EvenementRepository $evenementRepository): Response
    {
        return $this->render('profil/evenement.html.twig',[
            'evenements' => $evenementRepository->findAll(),
        ]);
    }
    #[Route('/profil/evenement/{id}/session/', name: 'app_profil-evenement-session')]
    public function session(SessionRepository $sessionRepository,$id): Response
    {   $session = $sessionRepository->findBy(['evenement' => $id],['debit' => 'ASC']);
        return $this->render('profil/session.html.twig',[
            'sessions' => $session,
            'id' => $id
        ]);
    }
}
