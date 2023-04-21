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
        // te5o les reservation mta3 current user !!
        $reservations = $reservationRepository->findBy(['user' => $user]);
        // 3ando 2 reservations
        // loula fiha (2 types billets )
        // thenia (3 types billets)
        foreach ($reservations as $reservation) {
            // jebt les 2 billets mta3 reservation loula
            $billetReservers = $billetReserverRepository->findBy(['reservation' => $reservation]);
            $resultatPrixReservation=0;
            $resultatNombreBillet=0;
            $result []=[] ;
            // bouclit 3lihom
            foreach ($billetReservers as $billetReserver) {
               $resultatPrixReservation += $billetReserver->getBillet()->getPrix();
               $resultatNombreBillet += $billetReserver->getNombre();

            }
        }
        return $this->render('profil/reservation.html.twig',[
            'reservations' => $reservations
        ]);
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
