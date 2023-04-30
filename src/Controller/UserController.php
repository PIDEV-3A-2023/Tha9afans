<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use League\Csv\Writer;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET']),IsGranted("ROLE_ADMIN")]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('query');
        $sortBy = $request->query->get('sort-by');

        switch($sortBy) {
            case 'cin':
                $users = $userRepository->findAllOrderByCin();
                break;
            case 'date_naissance':
                $users = $userRepository->findAllOrderByDateNaissance();
                break;
            default:
                $users = $userRepository->findAll();
                break;
        }

        if($query) {
            $users = $userRepository->searchByNomPrenomEmail($query);
        }
        if($request->isXmlHttpRequest()) {
            return $this->render('user/_users_table.html.twig', [
                'users' => $users
            ]);
        }
        // Check if a download request was made
        $download = $request->query->get('download');
        if($download) {
            // Create the CSV file
            $csv = Writer::createFromString('');
            $csv->insertOne([ 'CIN','Nom', 'Prenom','Email','Adresse','Telephone']);
            foreach ($users as $user) {
                $csv->insertOne([$user->getCin(),$user->getNom(), $user->getPrenom(), $user->getEmail(),$user->getAdresse(),$user->getTelephone()]);
            }

            // Send the CSV file as a response
            $response = new Response($csv->getContent());
            $response->headers->set('Content-Type', 'text/csv');
            $filename = sprintf('users_%s.csv', date('Y-m-d')); // current date in YYYY-MM-DD format
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
            return $response;
        }


        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }



    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'hide_password' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'hide_password' => false,
        ]);
    }


    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/usershow/{id}', name: 'user_show')]
    public function showphoto(User $user): Response
    {
        $photo = stream_get_contents($user->getPhoto());

        return new Response($photo, 200, ['Content-Type' => 'image/jpeg']);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository,MailerService $mailer): Response
    {
        $new=false;
        $form = $this->createForm(UserType::class, $user, [
            'hide_password' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*if ($new){
                $user->setCreatedBy($this->getUser());
            }*/

            // get uploaded file for photo field
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                // open file and get contents as string
                $photoContent = file_get_contents($photoFile->getRealPath());
                $user->setPhoto($photoContent);
            }

            $userRepository->save($user, true);






            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'hide_password' => true,
        ]);
    }


    #[Route('/{id}/block', name: 'block_user')]
    public function blockUser(User $user,MailerService $mailerService)
    {
        $user->setIsBlocked(true);
        $this->getDoctrine()->getManager()->flush();
        $mailerService->sendEmail(
            'fadhel.ons@esprit.tn',
            $user->getEmail(),
            'Bloquer compte utilisateur',
            'blockuser',
            ['content' => ''],
        );

        return $this->redirectToRoute('app_user_index');
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);

        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/rechercher', name: 'app_user_search')]
    public function search(Request $request)
    {
        $query = $request->query->get('query');
        $users = $this->getDoctrine()->getRepository(User::class)->findByNomPrenomEmail($query);

        $data = array();
        foreach ($users as $user) {
            $data[] = array(
                'id' => $user->getId(),
                'cin' => $user->getCin(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'datenaissance' =>$user->getDatenaissance(),
                'photo' => $user->getPhoto(),
            );
        }

        return new JsonResponse($data);
    }

}
