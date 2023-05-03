<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Produit;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\EvenementType;
use App\Form\ReservationType;
use App\Repository\BilletRepository;
use App\Repository\BilletReserverRepository;
use App\Repository\EvenementRepository;
use App\Repository\ProduitRepository;
use App\Repository\ReservationRepository;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
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
    #[Route('/profil/produit/', name: 'app_profil-produit1')]
    public function produit(ProduitRepository $produitRepository): Response
    {
        return $this->render('profil/produit.html.twig',[
            'produits' => $produitRepository->findAll(),
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

    #[Route('/profil/produit/', name: 'app_profil-produit1')]
    public function produitowner(ProduitRepository $produitRepository): Response
    {

        //find produit by user id
        $produits = $produitRepository->findBy(['idVendeur' => $this->getUser()]);
        return $this->render('profil/produit.html.twig',[
            'produits' => $produits,
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
    #[Route('/authentification2factor', name: 'acivate_authentification')]
    public function ActiverauthUser():Response
    {
        $user=$this->getUser();
        $user->setTwofactor(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('account_seetings');
    }
}
