<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Form\CommentaireType;
use App\Form\EvenementSearchType;
use App\Form\EvenementType;
use App\Repository\CommentaireRepository;
use App\Repository\EvenementRepository;
use App\Repository\GalerieRepository;
use App\Repository\JaimeRepository;
use App\Repository\SessionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{

    #[Route('/', name: 'app_evenement_index', methods: ['POST','GET'])]
    public function index(EvenementRepository $evenementRepository,Request $request,PaginatorInterface $paginator): Response
    {   $form = $this->createForm(EvenementSearchType::class);
        $form->handleRequest($request);
        $events=$evenementRepository->findAll();
        foreach ($events as $event){
            $rdata[] = [
                'id' => $event->getId(),
                'title' => $event->getNom(),
                'start' => $event->getDate()->format('Y-m-d'),
            ];
        }
        $data= json_encode($rdata);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $form->get('nom')->getData();
            $evenements = $evenementRepository->SearchByname($nom);
            $evenements = $paginator->paginate(
                $evenements,
                $request->query->getInt('page', 1),
                2,
            );
            return $this->render('evenement/index.html.twig', [
                'form' => $form->createView(),
                'evenements' => $evenements,
                'data'=>$data
            ]);
        }

        $events = $paginator->paginate(
            $evenementRepository->findAll(),
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('evenement/index.html.twig', [
            'form' => $form->createView(),
            'evenements' => $events,
            'data'=>$data
        ]);

    }


    /* #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
     public function new(Request $request, EvenementRepository $evenementRepository): Response
     {
         $evenement = new Evenement();
         $form = $this->createForm(EvenementType::class, $evenement);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $evenementRepository->save($evenement, true);

             return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
         }

         return $this->renderForm('evenement/new.html.twig', [
             'evenement' => $evenement,
             'form' => $form,
         ]);
     }*/
    #[Route('/new', name: 'app_profil-addevenement', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        $user= $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->setCreateur($user) ;
            if ($evenementRepository->findOneBy(['nom' => $evenement->getNom(),'date'=>$evenement->getDate(),'description'=>$evenement->getDescription()])){
                $this->addFlash('danger', 'Evenement deja existe!');
                return $this->redirectToRoute('app_profil-addevenement', [], Response::HTTP_SEE_OTHER);
            }
            $location = json_decode($request->request->get('location'));
// Access the latitude and longitude values using $location->lat and $location->lng

            $evenementRepository->save($evenement, true);
            $id = $evenement->getId();

            return $this->redirectToRoute('app_profil-evenement-session-add', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/addEvenement.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);

    }
    #[Route('/{id}/edit', name: 'app-evenement-edit', methods: ['GET', 'POST'])]
    public function editEvenement(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_profil-evenement-session', ['id'=>$evenement->getId()], Response::HTTP_SEE_OTHER  );
        }

        return $this->renderForm('profil/editEvenement.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET','POST'])]
    public function show(CommentaireRepository $commentaireRepository, Request $request, Evenement $evenement, SessionRepository $sessionRepository, GalerieRepository $galerieRepository,JaimeRepository $jaimeRepository): Response
    {   $jaime = $jaimeRepository->findOneBy(['User'=>$this->getUser(),'Event'=>$evenement]);
        $numberJaime = $jaimeRepository->count(['Event'=>$evenement]);
        $sessions = $sessionRepository->findBy(['evenement' => $evenement]);
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        $user = $this->getUser();
        $dateTime = new \DateTime();
        $currentDateTime = $dateTime->format('Y-m-d H:i:s');
        $dateTimeObject = \DateTime::createFromFormat('Y-m-d H:i:s', $currentDateTime);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setUser($user);
            $commentaire->setEvenement($evenement);
            $commentaire->setDate($dateTimeObject);
            $commentaireRepository->save($commentaire, true);

            return $this->json( [
                'user' => ['id' => $commentaire->getUser()->getId(), 'nom' => $commentaire->getUser()->getNom(), 'prenom' => $commentaire->getUser()->getPrenom()],
                'date' => $commentaire->getDate()->format('Y-m-d H:i'),
                'commentaire' => $commentaire->getCommentaire(),
                'id' => $commentaire->getId(),
            ]);
        }

        return $this->renderForm('evenement/show.html.twig', [
            'evenement' => $evenement,
            'sessions' => $sessions,
            'commentaires' => $commentaireRepository->findBy(['evenement' => $evenement]),
            'commentaire' => $commentaire,
            'form' => $form,
            'count'=>$numberJaime,
            'jaime'=>$jaime
        ]);
    }


    /*  #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
      public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
      {
          $form = $this->createForm(EvenementType::class, $evenement);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $evenementRepository->save($evenement, true);

              return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
          }

          return $this->renderForm('evenement/editSession.html.twig', [
              'evenement' => $evenement,
              'form' => $form,
          ]);
      }*/

    /* #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
     public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
     {
         if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
             $evenementRepository->remove($evenement, true);
         }

         return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
     }*/
    #[Route('/{id}', name: 'app-evenement-delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_profil-evenement', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/search', name: 'search', methods: ['GET', 'POST'])]
    public function search(Request $request, EvenementRepository $evenementRepository): JsonResponse
    {
        $name = $request->request->get('name');

        // perform the search using $query and return the results as an array
        $evenements = $evenementRepository->findByNom($name);

        $evenementsData = [];
        foreach ($evenements as $evenement) {
            $evenementsData[] = [
                'id' => $evenement->getId(),
                'nom' => $evenement->getNom(),
                'description' => $evenement->getDescription(),
            ];
        }

        return new JsonResponse($evenementsData);
    }

}
