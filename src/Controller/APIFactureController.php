<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class APIFactureController extends AbstractController
{
    #[Route('/apifacture', name: 'app_a_p_i_facture', methods: ['GET'])]
    public function AllFactures(FactureRepository $factureRepository):Response{

        $factures=$factureRepository->findAll();
        foreach ($factures as $facture){
            $rdata[] = [
                'id' => $facture->getId(),
                'tva' => $facture->getTva(),
                'refrancefacture'=>$facture->getRefrancefacture(),
                'useremail' => $facture->getIdCommende()->getIdUser()->getEmail(),
                //'nomproduit' => $facture->getIdCommende()->getIdProduit()->getNom(),
                'datefacture' => $facture->getDatefacture()->format('Y-m-d'),
                'totale' =>$facture->getIdCommende()->getTotal(),


            ];
        }

        return new JsonResponse($rdata,200);

    }

}