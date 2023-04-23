<?php

namespace App\Controller;

use App\Entity\QuizQuestion;
use App\Form\QuizQuestionType;
use App\Repository\QuizQuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quizquestion')]
class QuizQuestionController extends AbstractController
{
    #[Route('/', name: 'app_quiz_question_index', methods: ['GET'])]
    public function index(QuizQuestionRepository $quizQuestionRepository): Response
    {
        return $this->render('quiz_question/index.html.twig', [
            'quiz_questions' => $quizQuestionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quiz_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuizQuestionRepository $quizQuestionRepository): Response
    {
        $form = $this->createForm(QuizQuestionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quiz = $form->get('quiz')->getData();
            $questions = $form->get('questions')->getData();

            foreach ($questions as $question) {
                $quizQuestion = new QuizQuestion();
                $quizQuestion->setQuiz($quiz);
                $quizQuestion->setQuestion($question);
                $quizQuestionRepository->save($quizQuestion, true);
            }

            return $this->redirectToRoute('app_quiz_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_question/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_quiz_question_show', methods: ['GET'])]
    public function show(QuizQuestion $quizQuestion): Response
    {
        return $this->render('quiz_question/show.html.twig', [
            'quiz_question' => $quizQuestion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quiz_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuizQuestion $quizQuestion, QuizQuestionRepository $quizQuestionRepository): Response
    {
        $form = $this->createForm(QuizQuestionType::class, $quizQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizQuestionRepository->save($quizQuestion, true);

            return $this->redirectToRoute('app_quiz_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_question/edit.html.twig', [
            'quiz_question' => $quizQuestion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quiz_question_delete', methods: ['POST'])]
    public function delete(Request $request, QuizQuestion $quizQuestion, QuizQuestionRepository $quizQuestionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizQuestion->getId(), $request->request->get('_token'))) {
            $quizQuestionRepository->remove($quizQuestion, true);
        }

        return $this->redirectToRoute('app_quiz_question_index', [], Response::HTTP_SEE_OTHER);
    }
}
