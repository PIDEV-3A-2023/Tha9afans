<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

use App\Security\GoogleAuthenticatorController;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


class SecurityController extends AbstractController
{
    use TargetPathTrait;

    private $clientRegistry;
    private $em;
    private $router;
    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_login');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/oubli-pass', name: 'forgetten-password')]
    public function forgettenPassword(Request $request, UserRepository $userRepository,TokenGeneratorInterface $tokenGenerator,
    EntityManagerInterface $entityManager,MailerService $mailerService):Response
    {
        $form=$this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //on va chercher un utilisateur par son email
            $user=$userRepository->findOneBy(['email'=>$form->get('email')->getData()]);
            //on vérifie si on a un utilisateur
            if($user){
                //on génere un token de réintialisation
                $token=$tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
                //on générer un lien de réintialisation de mot de passe
                $url=$this->generateUrl('reset_pass',['token'=>$token],UrlGeneratorInterface::ABSOLUTE_URL);
                //On crée les données de email
                $context=compact('url','user');
                //envoi du mail
                $mailerService->sendEmail(
                    'fadhel.ons@esprit.tn',
                    $user->getEmail(),
                    'Réintialisation de mot de passe',
                    'password_reset',
                    $context
                );
                $this->addFlash('success','Email envoyé avec succés');
                return $this->redirectToRoute('app_login');
            }
            //il nya pas un utilisateur
            $this->addFlash('danger','Un probleme est sourvenu');
            return $this->redirectToRoute('app_login');

        }

        return $this->render('security/rest_password_request.html.twig',['resuestPassForm'=>$form->createView()]);

    }
    #[Route(path: '/oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(string $token,Request $request,UserRepository $userRepository,EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher):Response{
        //on vérifie si on a un token dans la base de donné
        $user=$userRepository->findOneBy(['resetToken'=>$token]);
        if($user){
            $form=$this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                //on supprime le token
                $user->setResetToken("");
                $user->setPassword(
                    $passwordHasher->hashPassword($user,$form->get('password')->getData())
                );
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success','Vote mot de passe est changé avec succés');
                return $this->redirectToRoute('app_login');
            }
            return $this->render('security/reset_password.html.twig',[
                'passForm'=>$form->createView()
            ]);

        }
        $this->addFlash('danger','jeton invalide');
        return $this->redirectToRoute('app_login');


    }
    private function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }

    private function fetchAccessToken($client)
    {
        $token = $client->getAccessToken();
        if (!$token) {
            throw new \Exception('Failed to fetch access token');
        }
        return $token;
    }

    #[Route(path: '/connect/google', name: 'connect_google_start')]
    public function redirectToGoogleAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect(['email'], []);
    }
    #[Route(path: '/connect/google/check', name: 'connect_google_check')]
    public function checkGoogleAction(
        Request $request,TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,MailerService $mailerService)
    {
        $accessToken = $this->fetchAccessToken($this->getGoogleClient());
        $googleUser = $this->clientRegistry
            ->getClient('google')
            ->fetchUserFromToken($accessToken);

        $email = $googleUser->getEmail();
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);
        $token = $tokenGenerator->generateToken();

        if (!$user) {
            $user = new User();
            $user->setEmail($googleUser->getEmail());
            $user->setNom($googleUser->getName());
            $user->setPhoto($googleUser->getAvatar());
            $user->setPassword('default-password');
            $user->setGenre('autre');

            $user->setResetToken($token);
            $this->em->persist($user);
            $this->em->flush();
            $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            //On crée les données de email
            $context = compact('url', 'user');
            //envoi du mail
            $mailerService->sendEmail(
                'fadhel.ons@esprit.tn',
                $user->getEmail(),
                'Réintialisation de mot de passe par gmail',
                'password_reset',
                $context
            );
            $this->addFlash('success','Email de réintialisation de mot de passe est envoyé avec succés');
            return $this->redirectToRoute('app_login');
        }
            $user->setResetToken($token);
            $this->em->persist($user);
            $this->em->flush();
            $url=$this->generateUrl('reset_pass',['token'=>$token],UrlGeneratorInterface::ABSOLUTE_URL);
            //On crée les données de email
            $context=compact('url','user');
            //envoi du mail
            $mailerService->sendEmail(
                'fadhel.ons@esprit.tn',
                $user->getEmail(),
                'Réintialisation de mot de passe',
                'password_reset',
                $context
            );
            $this->addFlash('success','Email de réintialisation de mot de passe est envoyé avec succés');
            return $this->redirectToRoute('app_login');
    }


}
