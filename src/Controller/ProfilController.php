<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\EvenementType;
use App\Form\ReservationType;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Repository\BilletRepository;
use App\Repository\BilletReserverRepository;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        public function reservation(Request $request, ReservationRepository $reservationRepository, BilletReserverRepository $billetReserverRepository, PaginatorInterface $paginator): Response
        {
            $user = $this->getUser();
            $reservations = $reservationRepository->findBy(['user' => $user]);
            foreach ($reservations as $reservation) {
                $billetReservers = $billetReserverRepository->findBy(['reservation' => $reservation]);
                $resultatPrixReservation = 0;
                $resultatNombreBillet = 0;
                foreach ($billetReservers as $billetReserver) {
                    $resultatPrixReservation += $billetReserver->getBillet()->getPrix();
                    $resultatNombreBillet += $billetReserver->getNombre();
                }
                $reservation->setTotalPrice($resultatPrixReservation);
                $reservation->setNombreBillet($resultatNombreBillet);
            }

            // Use the Paginator service to paginate the $reservations array
            $pagination = $paginator->paginate(
                $reservations,
                $request->query->getInt('page', 1),
                // Get the current page number from the request, default to 1
                3 // Limit the number of reservations displayed per page to 4
            );

            return $this->render('profil/reservation.html.twig', [
                'reservations' => $pagination,
            ]);
        }


    public function downloadPdfAction($reservationId, ReservationRepository $reservationRepository, BilletReserverRepository $billetReserverRepository,BilletRepository $billetRepository)
    {
        // Get the reservation and associated ticket information
        $reservation = $reservationRepository->find($reservationId);
        $billetReservers = $billetReserverRepository->findBy(['reservation' => $reservation]);

        // get billet from billetReserver
        $x = $billetReserverRepository->findOneBy(['reservation' => $reservation]);
        $eventId = $x->getBillet()->getEvenement()->getId();
        $billets = $billetRepository->findBy(['evenement' => $eventId]);
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
        // Render the ticket as HTML
        $html = $this->renderView('profil/ticket.html.twig', [
            'reservation' => $reservation,
            'billets' => $billets,
            'qrCodeTable' => $qrCodeTable,
            'billetReservers'=> $billetReservers
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
