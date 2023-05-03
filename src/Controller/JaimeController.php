<?php

namespace App\Controller;

use App\Entity\Jaime;
use App\Form\JaimeType;
use App\Repository\EvenementRepository;
use App\Repository\JaimeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jaime')]
class JaimeController extends AbstractController
{



    #[Route('/{eventId}', name: 'app_jaime', methods: ['POST','GET'])]
    public function likeEvent($eventId, JaimeRepository $jaimeRepository,EvenementRepository $evenementRepository): JsonResponse
    {
        $user= $this->getUser();
        // Check if the user has already liked the event
        $like = $jaimeRepository->findOneBy([
            'Event' => $eventId,
            'User' => $user
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


}
