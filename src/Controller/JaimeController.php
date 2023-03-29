<?php

namespace App\Controller;

use App\Entity\Jaime;
use App\Form\JaimeType;
use App\Repository\JaimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jaime')]
class JaimeController extends AbstractController
{
    #[Route('/', name: 'app_jaime_index', methods: ['GET'])]
    public function index(JaimeRepository $jaimeRepository): Response
    {
        return $this->render('jaime/index.html.twig', [
            'jaimes' => $jaimeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_jaime_new', methods: ['GET', 'POST'])]
    public function new(Request $request, JaimeRepository $jaimeRepository): Response
    {
        $jaime = new Jaime();
        $form = $this->createForm(JaimeType::class, $jaime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jaimeRepository->save($jaime, true);

            return $this->redirectToRoute('app_jaime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jaime/new.html.twig', [
            'jaime' => $jaime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jaime_show', methods: ['GET'])]
    public function show(Jaime $jaime): Response
    {
        return $this->render('jaime/show.html.twig', [
            'jaime' => $jaime,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jaime_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jaime $jaime, JaimeRepository $jaimeRepository): Response
    {
        $form = $this->createForm(JaimeType::class, $jaime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jaimeRepository->save($jaime, true);

            return $this->redirectToRoute('app_jaime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jaime/edit.html.twig', [
            'jaime' => $jaime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jaime_delete', methods: ['POST'])]
    public function delete(Request $request, Jaime $jaime, JaimeRepository $jaimeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jaime->getId(), $request->request->get('_token'))) {
            $jaimeRepository->remove($jaime, true);
        }

        return $this->redirectToRoute('app_jaime_index', [], Response::HTTP_SEE_OTHER);
    }
}
