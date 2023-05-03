<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Session;
use App\Repository\CategorieEvenementRepository;
use App\Repository\EvenementRepository;
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

        $category = $categorieRepository->findBy(['nom'=>$category]);
        $User = $userRepository->find($User);

        $evenement = new Evenement();
        $evenement->setNom($name);
        $evenement->setDescription($Description);
        $evenement->setAddresse($Adress);
        $evenement->setDate($Date);
        $evenement->setCategorie($category);
        $evenement->setCreateur($User);

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
    public function getEvent(EvenementRepository $evenementRepository,SessionRepository $sessionRepository):Response{

        $events=$evenementRepository->findAll();
        foreach ($events as $event){
            $sessions=$sessionRepository->findBy(['evenement'=>$event->getId()]);
            foreach ($sessions as $session) {
                $Sdata[]=[
                    'id'=>$session->getId(),
                    'titre'=>$session->getTitre(),
                    'description'=>$session->getDescription(),
                    'parlant'=>$session->getParlant(),
                    'debut'=>$session->getDebit()->format('H:i:s'),
                    'fin'=>$session->getFin()->format('H:i:s')];
            }
            $rdata[] = [
                'id' => $event->getId(),
                'title' => $event->getNom(),
                'description'=>$event->getDescription(),
                'date' => $event->getDate()->format('Y-m-d'),
                'Adress' => $event->getAddresse(),
                'category' => $event->getCategorie()->getNom(),
                'sessions'=>$Sdata,

            ];
        }

        return new JsonResponse($rdata,200);

    }

}
