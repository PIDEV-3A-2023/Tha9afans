<?php

namespace App\Controller;

use App\Entity\Stripep;
use App\Form\StripepFormType;
use App\Form\StripepType;
use App\Repository\StripepRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stripep')]
class StripepController extends AbstractController
{
    #[Route('/', name: 'app_stripep_index', methods: ['GET'])]
    public function index(StripepRepository $stripepRepository): Response
    {
        return $this->render('stripep/index.html.twig', [
            'stripeps' => $stripepRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stripep_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StripepRepository $stripepRepository): Response
    {
        $stripep = new Stripep();
        $form = $this->createForm(StripepFormType::class, $stripep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stripepRepository->save($stripep, true);

            return $this->redirectToRoute('app_stripep_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stripep/new.html.twig', [
            'stripep' => $stripep,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stripep_show', methods: ['GET'])]
    public function show(Stripep $stripep): Response
    {
        return $this->render('stripep/show.html.twig', [
            'stripep' => $stripep,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stripep_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stripep $stripep, StripepRepository $stripepRepository): Response
    {
        $form = $this->createForm(StripepType::class, $stripep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stripepRepository->save($stripep, true);

            return $this->redirectToRoute('app_stripep_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stripep/edit.html.twig', [
            'stripep' => $stripep,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stripep_delete', methods: ['POST'])]
    public function delete(Request $request, Stripep $stripep, StripepRepository $stripepRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stripep->getId(), $request->request->get('_token'))) {
            $stripepRepository->remove($stripep, true);
        }

        return $this->redirectToRoute('app_stripep_index', [], Response::HTTP_SEE_OTHER);
    }
}
