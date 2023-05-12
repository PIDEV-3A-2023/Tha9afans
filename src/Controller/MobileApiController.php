<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Entity\Jaime;
use App\Entity\Session;
use App\Repository\CategorieEvenementRepository;
use App\Repository\CommentaireRepository;
use App\Repository\EvenementRepository;
use App\Repository\JaimeRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MobileApiController extends AbstractController
{
    #[Route('/mobile/api', name: 'app_mobile_api')]
    public function index(): Response
    {
        return $this->render('mobile_api/index.html.twig', [
            'controller_name' => 'MobileApiController',
        ]);
    }
    #[Route('/event/add', name: 'add_event')]
    public function AddEvent(Request $request, EvenementRepository $evenementRepository,CategorieEvenementRepository $categorieRepository,UserRepository $userRepository): Response
    {
        $name = $request->query->get("name");
        $Description = $request->query->get("Description");
        $Adress = $request->query->get("Adress");
        $Date = $request->query->get("Date");
        $category = $request->query->get("category");
        $User = $request->query->get("User");


        $category = $categorieRepository->find($category);
        $User = $userRepository->find($User);

        $evenement = new Evenement();
        $evenement->setNom($name);
        $evenement->setDescription($Description);
        $evenement->setAddresse($Adress);
        $evenement->setDate(new \DateTime($Date));
        $evenement->setCategorie($category);
        $evenement->setCreateur($User);
        $evenement->setLocalisation("");
        $evenement->setFreeorpaid(true);
        $evenement->setOnline(true);
        $evenement->setLink("");

        try {
           $evenementRepository->save($evenement, true);
            return new JsonResponse(["Evenement est créé",$evenement->getId()], 200);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage(), 400);
        }
    }
    #[Route('/Session/add', name: 'add_Session')]
    public function AddSession(Request $request, EvenementRepository $evenementRepository,SessionRepository $sessionRepository): Response
    {
        $titre = $request->query->get("titre");
        $Description = $request->query->get("Description");
        $Host = $request->query->get("Host");
        $DateDebut = $request->query->get("Debut");
        $DateFin = $request->query->get("Fin");
        $evenement = $request->query->get("evenement");

        $DateDebut = new \DateTime($DateDebut);
        $DateFin = new \DateTime($DateFin);
        $evenement = $evenementRepository->find($evenement);
        $session = new Session();
        $session->setTitre($titre);
        $session->setDescription($Description);
        $session->setParlant($Host);
        $session->setDebit($DateDebut);
        $session->setFin($DateFin);
        try {
            $evenementRepository->save($evenement, true);
            return new JsonResponse("Session Ajoutee", 200);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage(), 400);
        }
    }
    #[Route('/event/get', name: 'get_event')]
    public function getEvent(EvenementRepository $evenementRepository, SessionRepository $sessionRepository): Response
    {
        $events = $evenementRepository->findAll();
        $rdata = [];

        foreach ($events as $event) {
            $sessions = $sessionRepository->findBy(['evenement' => $event->getId()]);
            $Sdata = []; // Initialize the $Sdata variable here

            foreach ($sessions as $session) {
                $Sdata[] = [
                    'id' => $session->getId(),
                    'titre' => $session->getTitre(),
                    'description' => $session->getDescription(),
                    'parlant' => $session->getParlant(),
                    'debut' => $session->getDebit()->format('H:i:s'),
                    'fin' => $session->getFin()->format('H:i:s')
                ];
            }

            $rdata[] = [
                'id' => $event->getId(),
                'title' => $event->getNom(),
                'description' => $event->getDescription(),
                'date' => $event->getDate()->format('Y-m-d'),
                'Adress' => $event->getAddresse(),
                'category' => $event->getCategorie()->getNom(),
                'sessions' => $Sdata,
            ];
        }

        return new JsonResponse($rdata, 200);
    }
    #[Route('/categoriesev/get', name: 'get_cat')]
    public function getCategories(CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        $cats = $categorieEvenementRepository->findAll();
        $rdata = [];
        foreach ($cats as $cat) {
            $rdata[] = [
                'id' => $cat->getId(),
                'nom'=> $cat->getNom(),
            ];
        }

        return new JsonResponse($rdata, 200);
    }


    #[Route('/jaime',  methods: ['POST','GET'])]
    public function likeEvent(JaimeRepository $jaimeRepository,EvenementRepository $evenementRepository,Request $request,UserRepository $userRepository): JsonResponse
    {
        $user=$request->query->get("user");
        $eventId=$request->query->get("event");
        // Check if the user has already liked the event
        $like = $jaimeRepository->findOneBy([
            'Event' => $evenementRepository->find($eventId),
            'User' => $userRepository->find($user),
        ]);


        if ($like) {
            // User has already liked the event, so remove the like
            $jaimeRepository->remove($like, true);
            $numberJaime = $jaimeRepository->count(['Event'=>$eventId]);
            return new JsonResponse( ['action' => 'unliked','number'=>$numberJaime]);
        } else {
            // User has not yet liked the event, so save the like
            $like = new jaime();
            $like->setEvent($evenementRepository->find($eventId));
            $like->setUser($user);
            $jaimeRepository->save($like, true);
            $numberJaime = $jaimeRepository->count(['Event'=>$eventId]);
            return new JsonResponse( ['action' => 'liked','number'=>$numberJaime]);
        }
    }
    #[Route('/comment/add', name: 'add_comment')]
    public function comment(Request $request, EvenementRepository $evenementRepository,CommentaireRepository $commentaireRepository,UserRepository $userRepository): Response
    {
        $contenu = $request->query->get("contenu");
        $evenement = $request->query->get("eventid");
        $User = $request->query->get("userid");
        $dateTime = new \DateTime();
        $currentDateTime = $dateTime->format('Y-m-d H:i:s');
        $dateTimeObject = \DateTime::createFromFormat('Y-m-d H:i:s', $currentDateTime);
        $evenement = $evenementRepository->find($evenement);
        $User = $userRepository->find($User);

        $commentaire = new Commentaire();
        $commentaire->setcommentaire($contenu);
        $commentaire->setDate($dateTimeObject);
        $commentaire->setEvenement($evenement);
        $commentaire->setUser($User);


        try {
            $commentaireRepository->save($commentaire, true);
            return new JsonResponse(["Commentaire est créé",$commentaire->getId()], 200);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage(), 400);
        }
    }
    #[Route('/comment/get', name: 'get_comment')]
    public function getComment(CommentaireRepository $commentaireRepository, UserRepository $userRepository): Response
    {
        $comments = $commentaireRepository->findAll();
        $rdata = [];
        foreach ($comments as $comment) {
            $name =$comment->getUser()->getNom()." ".$comment->getUser()->getPrenom();
            $rdata[] = [
                'id' => $comment->getId(),
                'contenu' => $comment->getcommentaire(),
                'date' => $comment->getDate()->format('Y-m-d H:i:s'),
                'user' =>$name   ,
                'Userid'=>$comment->getUser()->getId(),
                'eventId' => $comment->getEvenement()->getId(),
            ];
        }

        return new JsonResponse($rdata, 200);
    }
    #[Route('/comment/del', name: 'Del_comment')]
    public function delcomment(Request $request,CommentaireRepository $commentaireRepository): Response
    {
        $id = $request->query->get("id");
        $commentaire = $commentaireRepository->find($id);

        try {
            $commentaireRepository->remove($commentaire, true);
            return new JsonResponse(["Commentaire deleted"], 200);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage(), 400);
        }
    }
}
