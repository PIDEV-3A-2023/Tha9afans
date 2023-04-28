<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CodeVerificationType;




class Authentification2facteurController extends AbstractController
{
    #[Route('/authentification2facteur', name: 'app_authentification2facteur')]
    public function sendCodeAction()
    {
        // Récupérer le numéro de téléphone de l'utilisateur connecté dans la session
        $user = $this->getUser();
        $phoneNumber = $user->getTelephone();

        // Générer un code aléatoire à six chiffres
        $code = random_int(100000, 999999);

        // Stocker le code dans la session
        $this->get('session')->set('code', $code);

        // Envoyer le code par SMS à l'utilisateur connecté
        $sid    = $_ENV['TWILIO_ACCOUNT_SID'];
        $token  = $_ENV['TWILIO_AUTH_TOKEN'];
        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            $phoneNumber, // Numéro de téléphone destinataire
            array(
                'from' => $_ENV['TWILIO_PHONE_NUMBER'], // Votre numéro Twilio
                'body' => 'Votre code de vérification est : ' . $code // Le corps du message SMS
            )
        );

        // Retourne une réponse pour confirmer l'envoi
        return new Response('Code envoyé avec succès : ' . $message->sid . $phoneNumber);
    }
    #[Route('/verifiercodesms', name: 'verifiercodesms')]
    public function verifierCodeAction(Request $request)
    {
        $error = null;
        $form = $this->createForm(CodeVerificationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get('code')->getData();
            $storedCode = $this->get('session')->get('code');

            if ($code == $storedCode) {
                // Code correct, faire ce que vous voulez ici
                $error='Code correct';
            } else {
                // Code incorrect
                $error='Code incorrect';
            }
        }

        return $this->render('authentification2facteur/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }


}
