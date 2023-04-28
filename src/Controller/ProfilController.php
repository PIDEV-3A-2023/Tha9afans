<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\ChangePasswordType;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
            'sessions' => $session
        ]);
    }
    #[Route('/profil/seetings', name: 'account_seetings')]
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the current password is correct
            $isCurrentPasswordValid = $passwordEncoder->isPasswordValid($user, $form->get('currentPassword')->getData());
            if (!$isCurrentPasswordValid) {
                $this->addFlash('danger', 'Le mot de passe actuel est incorrect.');
            }

            // Encode the new password and update the user
            $newPassword = $form->get('password')->getData();
            $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setPassword($encodedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
