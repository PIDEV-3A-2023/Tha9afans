<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeproduitRepository;
use App\Repository\CommandeRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use SendGrid\Mail\Mail;
use SendGrid;


use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;







#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository ): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]);
    }



    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository): Response
    {

        $facture = new Facture();
        $facture->setRefrancefacture(uniqid());
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/facture/pdf/{id}', name: 'app_facture_pdf', methods: ['GET'])]
    public function downloadPdfAction($id , CommandeproduitRepository $commandeproduitRepository  , FactureRepository $factureRepository): Response
    {
        // Get the facture entity by ID

        $facture = $this->getDoctrine()->getRepository(Facture::class)->find($id);


        // If no facture found, throw exception
        if (!$facture) {
            throw $this->createNotFoundException('No facture found for id '.$id);
        }

        // Retrieve all products in the command
/*        $commandeProduits = $commandeproduitRepository->findBy(['idCommande' => $facture->getIdCommende()]);*/

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options

        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file


        $html = $this->renderView('facture/pdf.html.twig', [
            'facture' => $facture,

        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF

        $dompdf->render();

        // Output the generated PDF to Browser (force download)

        $dompdf->stream("facture.pdf", [
            "Attachment" => true
        ]);

        // Write some HTML code:

        return new Response($html);
    }



    //create a function that show the facture of user connected
    #[Route('/facture/showfacture', name: 'app_facture_showfacture', methods: ['GET'])]
    public function showfacture(FactureRepository $factureRepository ,CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->findcommandeByUser($this->getUser());
        $facture = $factureRepository->findBy(['idCommende' => $commande]);

        return $this->render('facture/index.html.twig', [
            'factures' => $facture,


        ]);
    }










}
