<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\EvenementType;
use App\Form\ReservationType;
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
    public function reservation(ReservationRepository $reservationRepository): Response
    {
        return $this->render('profil/reservation.html.twig',[
            'reservations' => $reservationRepository->findAll()
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
