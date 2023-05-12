<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class UserApiController extends AbstractController
{
    #[Route('/user/signup', name: 'app_signup')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $email = $request->query->get("email");
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $cin = $request->query->get("cin");
        $roles = $request->query->get("roles");
        $password = $request->query->get("password");
        $telephone = $request->query->get("telephone");
        $adresse = $request->query->get("adresse");
        $datenaissance = $request->query->get("datenaissance");
        $genre = $request->query->get("genre");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response("adresse email invalide", 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setCin($cin);
        $user->setRoles([]);
        $user->setIsVerified(true);
        $user->setIsBlocked(false);
        $user->setTwofactor(false);
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $user->setTelephone($telephone);
        $user->setGenre($genre);
        $user->setAdresse($adresse);
        $user->setDatenaissance(new \DateTime($datenaissance));

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("utilisateur est créé", 200);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage(), 400);
        }
    }

    #[Route('/user/signin', name: 'app_signin')]
    public function signInAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            if ($passwordEncoder->isPasswordValid($user, $password)) {
                return new JsonResponse([
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'nom'=> $user->getNom(),
                    'prenom'=>$user->getPrenom(),
                    'cin'=> $user->getCin(),
                    'adresse' => $user->getAdresse(),
                    'telephone' =>$user->getTelephone(),
                    'genre'=>$user->getGenre(),
                    'email' => $user->getEmail(),
                    'password' =>$user->getPassword(),
                    'roles' => $user->getRoles(),
                    'datenaissane'=> $user->getDateNaissance()
                ], 200);
            } else {
                return new Response("Mot de passe incorrect", 400);
            }
        } else {
            return new Response("Email introuvable", 400);
        }
    }
    #[Route('/user/editUser', name: 'app_user_edituser')]
    public function modifierUser(Request $request, UserPasswordEncoderInterface $passwordEncoder){
       $id= $request->get('id');
       $nom=$request->get("nom");
       $prenom=$request->get("prenom");
       $email=$request->get("email");
       /*$password=$request->get("password");*/
       $cin=$request->get("cin");
       $telephone = $request->query->get("telephone");
       $adresse = $request->query->get("adresse");
       $roles = $request->query->get("roles");
       $datenaissance = $request->query->get("datenaissance");
       $genre = $request->query->get("genre");
       $user=$this->getDoctrine()->getRepository(User::class)->find($id);
       $photoFile = $request->files->get("photo");

        if ($photoFile) {
            // open file and get contents as string
            $photoContent = file_get_contents($photoFile->getRealPath());
            $user->setPhoto($photoContent);
        }
        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setCin($cin);
        $user->setRoles([$roles]);
        $user->setIsVerified(true);
        $user->setIsBlocked(false);
        $user->setAdresse($adresse);
        $user->setTwofactor(false);
        /*$user->setPassword($passwordEncoder->encodePassword($user, $password));*/
        $user->setTelephone($telephone);
        $user->setGenre($genre);
        $user->setDatenaissance(new \DateTime($datenaissance));
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("utilisateur est modifié avec succés", 200);
        } catch (\Exception $ex) {
            return new Response("vérifier vos données" .$ex->getMessage(), 400);
        }


    }
    #[Route('/user/getPasswordByEmail', name: 'app_passwordemail')]
    public function getPasswordByEmail(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $email=$request->get("email");
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email'=>$email]);
        if ($user){
            $password=$user->getPassword();
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            $formatted = $serializer->serialize($password, 'json');
            return new JsonResponse($formatted);
        }
        return new Response("user not found");

    }




}
