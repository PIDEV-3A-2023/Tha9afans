<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ResultsController extends AbstractController
{
    #[Route('/results', name: 'app_results')]
    public function index(SessionInterface $session): Response
    {
        $score = $session->get('quizScore');
        return $this->render('results/index.html.twig', [
            'score' => $score
        ]);
    }


}
