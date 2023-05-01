<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Produit;
use App\Repository\EvenementRepository;
use App\Repository\ProduitRepository;
use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(UserRepository $userRepository, ProduitRepository $produitRepository, EvenementRepository $evenementRepository, QuizRepository $quizRepository,): Response
    {
        $userCount = $userRepository->count([]);
        $productCount = $produitRepository->count([]);
        $eventCount = $evenementRepository->count([]);
        $quizzCount = $quizRepository->count([]);

        // Fetch data from database (e.g. using a repository)
        $utilisateursParRegion = $userRepository->countByAdresse();

        $labels = [];
        $data = [];

        foreach ($utilisateursParRegion as $utilisateurParRegion) {
            $labels[] = $utilisateurParRegion['adresse'];
            $data[] = $utilisateurParRegion['count'];
        }

        // Fetch data from database for the pie chart
        $utilisateursParGenreEtAge = $userRepository->countByAgeAndGender();

        $datapie = [
            'age' => [
                '< 18' => 0,
                '>= 18' => 0,
            ],
            'genre' => [
                'femme' => 0,
                'homme' => 0,
                'autre' => 0,
            ],
        ];

        foreach ($utilisateursParGenreEtAge as $utilisateur) {
            if ($utilisateur['datenaissance']) {
                $age = date_diff($utilisateur['datenaissance'], date_create('now'))->y;
                if ($age < 18) {
                    $datapie['age']['< 18'] += $utilisateur['count'];
                } else {
                    $datapie['age']['>= 18'] += $utilisateur['count'];
                }
            }
            if (isset($datapie['genre'][$utilisateur['genre']])) {
                $datapie['genre'][$utilisateur['genre']] += $utilisateur['count'];
            }
        }
        $datapiegenre = [
            'genre' => [
                'femme' => 0,
                'homme' => 0,
                'autre' => 0,
            ],
        ];

        $total = 0;
        foreach ($utilisateursParGenreEtAge as $utilisateurgenre) {
            if (isset($datapiegenre['genre'][$utilisateurgenre['genre']])) {
                $datapiegenre['genre'][$utilisateurgenre['genre']] += $utilisateurgenre['count'];
                $total += $utilisateurgenre['count'];
            }
        }

        if ($total > 0) {
            foreach ($datapiegenre['genre'] as &$value) {
                $value = round($value / $total * 100, 2);
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'userCount' => $userCount,
            'productCount' => $productCount,
            'eventCount' => $eventCount,
            'quizCount' => $quizzCount,
            'labels' => json_encode($labels),
            'data' => json_encode($data),
            'datapie' => json_encode($datapie),
            'datapiegenre'=>json_encode($datapiegenre),
        ]);
    }


}
