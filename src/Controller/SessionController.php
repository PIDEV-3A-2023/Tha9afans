<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Evenement;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/session')]
class SessionController extends AbstractController
{
    #[Route('/', name: 'app_session_index', methods: ['GET'])]
    public function index(SessionRepository $sessionRepository): Response
    {
        return $this->render('session/index.html.twig', [
            'sessions' => $sessionRepository->findAll(),
        ]);
    }

  /*  #[Route('/new', name: 'app_session_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SessionRepository $sessionRepository): Response
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sessionRepository->save($session, true);

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('session/new.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }*/
    #[Route('/{id}/new', name: 'app_profil-evenement-session-add', methods: ['GET', 'POST'])]
    public function newSession(Request $request,Evenement $evenement, SessionRepository $sessionRepository): Response
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->setEvenement($evenement);
            if($form->getData()->getEnd() < $form->getData()->getDate()){
                $this->addFlash('danger', 'Date de fin doit etre superieur a la date de debut!');
                return $this->redirectToRoute('app_profil-evenement-session-add', ['id' => $evenement->getId()], Response::HTTP_SEE_OTHER);
            }
            if ($sessionRepository->findOneBy(['nom' => $session->getNom(),'parlant'=>$session->getParlant(),'date'=>$session->getDate(),'debit'=>$session->getDebit(),'end'=>$session->getEnd(),'evenement'=>$session->getEvenement()])) {
                $this->addFlash('danger', 'Session deja existe!');
                return $this->redirectToRoute('app_profil-evenement-session-add', ['id' => $evenement->getId()], Response::HTTP_SEE_OTHER);
            }
            if($sessionRepository->save($session, true)){
                $this->addFlash('success', 'Session ajouter avec succes!');
                return $this->redirectToRoute('app_profil-evenement-session-add', ['id' => $evenement->getId()], Response::HTTP_SEE_OTHER);
                }
        }

        return $this->renderForm('profil/addSession.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_session_show', methods: ['GET'])]
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_session_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Session $session, SessionRepository $sessionRepository): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sessionRepository->save($session, true);

            return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('session/edit.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_session_delete', methods: ['POST'])]
    public function delete(Request $request, Session $session, SessionRepository $sessionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$session->getId(), $request->request->get('_token'))) {
            $sessionRepository->remove($session, true);
        }

        return $this->redirectToRoute('app_session_index', [], Response::HTTP_SEE_OTHER);
    }
}
