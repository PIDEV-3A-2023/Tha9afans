<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Facture;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository,FactureRepository $factureRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
            'factureRepository' => $factureRepository,
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande, true);
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }





    #[Route('/facture/{id}', name: 'app_commande_facture', methods: ['GET'])]
    public function generateFacture($id, FactureRepository $factureRepository) : Response
    {
        // Retrieve the order details corresponding to the Facture button's row
        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($id);

        // Check if a Facture already exists for the given Commande
        $existingFacture = $factureRepository->findOneBy(['idCommende' => $commande]);

        // If a Facture already exists, display a message to the user
        if ($existingFacture) {
            $this->addFlash('info', 'A facture already exists for this commande.');

            return $this->redirectToRoute('app_commande_index');
        }

        // If no Facture exists for the given Commande, create a new one
        $facture = new Facture();
        $facture->setDateFacture(new \DateTime());
        $facture->setRefrancefacture('REF' . substr(uniqid('', true), 0, 10)); // generate a random string starting with "REF"
        $facture->setTva(10);
        $facture->setIdCommende($commande);
        $factureRepository->save($facture, true);

        // Display a message to the user indicating that the Facture was successfully generated
        $this->addFlash('success', 'Facture generated successfully.');

        return $this->redirectToRoute('app_commande_index');
    }





}
