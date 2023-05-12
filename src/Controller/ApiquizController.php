<?php

namespace App\Controller;

use App\Repository\QuizQuestionRepository;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ApiquizController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/apiquiz', name: 'apiquiz')]
    public function getAllQuizzes(QuizRepository $quizRepository): JsonResponse
    {
        $quizzes = $quizRepository->findAll();
        foreach ($quizzes as $quiz) {
            $QuizData[] = [
                'id' => $quiz->getQuizId(),
                'name' => $quiz->getQuizName(),
                'description' => $quiz->getQuizDescription(),
                'image' => base64_encode(stream_get_contents($quiz->getQuizCover())),
                'numberOfQuestions' => $quiz->getNbrQuestions(),
            ];
        }
        return new JsonResponse($QuizData, 200);
    }

    #[Route('/apiquizquestion', name: 'apiquizquestion')]
    public function getQuizQuestion (QuizQuestionRepository $quizQuestionRepository): JsonResponse
    {
        $quizQuestions = $quizQuestionRepository->findAll();
        foreach ($quizQuestions as $quizQuestion) {
            $QuizQuestionData[] = [
                'quiz Name' => $quizQuestion->getQuiz()->getQuizName(),
                'question' => $quizQuestion->getQuestion()->getQuestion(),
            ];
        }
        return new JsonResponse($QuizQuestionData, 200);
    }



}
