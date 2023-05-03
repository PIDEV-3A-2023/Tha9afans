<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\Response;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class GoogleAuthenticatorController extends SocialAuthenticator implements AuthenticatorInterface
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

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() == '/connect/google/check' && $request->isMethod('GET');
    }

    public function authenticate(Request $request): Passport
    {
        $accessToken = $this->fetchAccessToken($this->getGoogleClient());
        $googleUser = $this->clientRegistry
            ->getClient('google')
            ->fetchUserFromToken($accessToken);

        $email = $googleUser->getEmail();
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($googleUser->getEmail());
            $user->setNom($googleUser->getName());
            $user->setPhoto($googleUser->getAvatar());
            $user->setPassword('default-password');
            $this->em->persist($user);
            $this->em->flush();
        }

        $passeport=new Passport(
            new UserBadge($user->getUserIdentifier()),
            new PasswordCredentials($user->getPassword()),
            [
                (new RememberMeBadge())->enable(),
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
        // Create an array of badges
        dd($passeport);
        return $passeport;
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Set authenticated user in session
        $request->getSession()->set('_security_'.$firewallName, serialize($token));

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $roles = $token->getRoleNames();

        if (in_array('ROLE_ADMIN', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_user_index'));
        } else {
            return new RedirectResponse($this->urlGenerator->generate('app_panier_produit_index'));
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        throw new CustomUserMessageAuthenticationException($exception->getMessage());
    }

    private function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }
    public function createAuthenticatedToken(PassportInterface|UserInterface $passport, string $firewallName): TokenInterface
    {
        $user = $passport->getUser();

        // create a user badge for the user
        $userBadge = new UserBadge((string) $user->getUsername(), function () use ($user) {
            return $this->userRepository->loadUserByUsername($user->getUsername());
        });

        // create a new authenticated token
        $token = new PostAuthenticationGuardToken($userBadge, $firewallName, $user->getRoles());

        return $token;
    }





    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method TokenInterface createToken(Passport $passport, string $firewallName)
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getGoogleClient());
    }


    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $googleUser = $this->clientRegistry
            ->getClient('google')
            ->fetchUserFromToken($credentials);

        $email = $googleUser->getEmail();

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($googleUser->getEmail());
            $user->setNom($googleUser->getName());
            $user->setPhoto($googleUser->getAvatar());
            $user->setPassword('default-password');
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }
}
