<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizHomeController extends AbstractController
{
    #[Route('/quizHomeBack', name: 'app_quiz_home_back')]
    public function index(): Response
    {
        return $this->render('quizHome/quizHomeBack.html.twig', [
            'controller_name' => 'QuizHomeController',
        ]);
    }


}
