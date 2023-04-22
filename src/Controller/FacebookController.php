<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Facebook;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class FacebookController extends AbstractController
{
    public function __construct()
    {
        $this->provider = new Facebook([
            'clientId' => $_ENV['FCB_ID'],
            'clientSecret' => $_ENV['FCB_SECRET'],
            'redirectUri' => $_ENV['FCB_CALLBACK'],
            'graphApiVersion' => 'v15.0',
        ]);
    }

    #[Route('/fcb-login', name: 'fcb_login')]
    public function fcbLogin(): Response
    {

        $helper_url = $this->provider->getAuthorizationUrl();

        return $this->redirect($helper_url);
    }


    #[Route('/fcb-callback', name: 'fcb_callback')]
    public function fcbCallBack(UserRepository $userDb, EntityManagerInterface $manager): Response
    {
        //Récupérer le token
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        try {
            // Refresh the token if it has expired
            if ($token->hasExpired()) {
                $token = $this->provider->getAccessToken('refresh_token', [
                    'refresh_token' => $token->getRefreshToken(),
                ]);
            }

            //Récupérer les informations de l'utilisateur
            $user = $this->provider->getResourceOwner($token);

            $user = $user->toArray();

            $email = $user['email'];

            $nom = $user['name'];

            //Vérifier si l'utilisateur existe dans la base des données
            $user_exist = $userDb->findOneByEmail($email);

            if ($user_exist) {
                $user_exist->setNom($nom);

                // Check if $user_exist has an id property before calling flush()
                if ($user_exist->getId()) {
                    $manager->flush();
                }

                return $this->render('user/index.html.twig', [
                    'nom' => $nom,
                ]);
            } else {
                $new_user = new User();

                $new_user->setNom($nom)
                    ->setEmail($email)
                    ->setPassword(sha1(str_shuffle('abscdop123390hHHH;:::OOOI')));

                $manager->persist($new_user);

                $manager->flush();

                return $this->render('user/index.html.twig', [
                    'nom' => $nom,
                ]);
            }
        } catch (\Throwable $th) {
            // Create a response object with the error message
            $errorMessage = $th->getMessage();
            $response = new Response($errorMessage, Response::HTTP_INTERNAL_SERVER_ERROR);

            return $response;
        }
    }


}