<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Entity\Score;
use App\Form\QuizType;
use App\Repository\QuestionRepository;
use App\Repository\QuizQuestionRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz')]
class QuizController extends AbstractController
{
    // for the backoffice
    #[Route('/', name: 'app_quiz_index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }


    #[Route('/{id}/start', name: 'app_quiz_question_start', methods: ['GET'])]
    public function startQuiz(Quiz $quiz, QuizQuestionRepository $quizQuestionRepository): Response
    {
        // Get all questions for the given quiz
        $questions = $quizQuestionRepository->findBy(['quiz' => $quiz]);

        // Shuffle the questions to get a random order
        shuffle($questions);

        // Create a session variable to store the questions and set it to the shuffled questions
        $this->get('session')->set('quizQuestions', $questions);

        // Redirect to the first question
        $question = reset($questions);
        return $this->redirectToRoute('app_quiz_question_show', [
            'quizId' => $quiz->getQuizId(),
            'questionId' => $question->getQuestion()->getQuestionId(),
        ]);
    }

    #[Route('/{quizId}/question/{questionId}', name: 'app_quiz_question_answer', methods: ['POST', 'GET'])]
    public function answerQuestion(Request $request, Quiz $quiz, Question $question, QuizQuestionRepository $quizQuestionRepository): Response
    {
        $answerId = $request->request->get('answer');
        $correctAnswerId = $question->getAnswer();

        $quizQuestions = $this->get('session')->get('quizQuestions');

        foreach ($quizQuestions as $quizQuestion) {
            if ($quizQuestion->getQuestion()->getQuestionId() == $question->getQuestionId()) {
                if ($answerId == $correctAnswerId) {
                    $quizQuestion->setIsCorrect(true);
                } else {
                    $quizQuestion->setIsCorrect(false);
                    $this->addFlash('error', 'Wrong answer! Please try again.');
                }
                break;
            }
        }

        // Check if there are any unanswered questions
        $unansweredQuestions = array_filter($quizQuestions, function($quizQuestion) {
            return !$quizQuestion->getIsCorrect();
        });

        if (count($unansweredQuestions) > 0) {
            // Redirect to the next unanswered question
            $nextQuestion = reset($unansweredQuestions);
            return $this->redirectToRoute('app_quiz_question_show', ['quizId' => $quiz->getQuizId(), 'questionId' => $nextQuestion->getQuestion()->getQuestionId(), 'id' => $nextQuestion->getQuestion()->getQuestionId()]);
        } else {
            // Calculate score
            $score = 0;
            foreach ($quizQuestions as $quizQuestion) {
                if ($quizQuestion->getIsCorrect()) {
                    $score += 100;
                }
            }

            // Store score in session
            $this->get('session')->set('quizScore', $score);

            // Redirect to results page
            return $this->redirectToRoute('app_results');
        }
    }



    #[Route('/quiz/{id}/score/{score}', name: 'app_quiz_score')]
    public function scoreQuiz(int $id, int $score, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the quiz and create a new Score object
        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->find($id);
        $scoreObject = new Score();
        $scoreObject->setQuiz($quiz);
        $scoreObject->setScore($score);

        // Persist the score object to the database
        $entityManager->persist($scoreObject);
        $entityManager->flush();

        // Redirect to the score index page
        return $this->redirectToRoute('app_score_index');
    }


    // it creates the quiz home page and converts the timer
    #[Route('/homeQuiz', name: 'app_quiz_Home')]
    public function homequizz(QuizRepository $quizRepository ,QuizQuestionRepository $quizQuestionRepository): Response
    {
        $quizzes = $quizRepository->findAll();
        $timers = array();

        foreach ($quizzes as $quiz) {
            $questions = $quizQuestionRepository->findBy(['quiz' => $quiz->getQuizId()]);
            $time = 0;
            foreach ($questions as $question) {
                $time += $question->getQuestion()->getTimer();
            }
            $timers[] = $time;
        }

        $timerFormatted = array();
        foreach ($timers as $timer) {
            if ($timer < 60) {
                $timerFormatted[] = $timer . ' seconds';
            } else if ($timer < 3600) {
                $timerFormatted[] = floor($timer / 60) . ' minutes';
            } else {
                $timerFormatted[] = floor($timer / 3600) . ' hours';
            }
        }

        return $this->render('quiz/quizHome.html.twig', [
            'quizzes' => $quizzes,
            'timer' => $timerFormatted,
        ]);
    }


    #[Route('/new', name: 'app_quiz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuizRepository $quizRepository, QuestionRepository $questionRepository): Response
    {
        $quiz = new Quiz();
        $question = $questionRepository->findAll();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // get uploaded file for photo field
            $photoFile = $form->get('quizCover')->getData();

            if ($photoFile) {
                $photoContent = file_get_contents($photoFile->getRealPath());
                $quiz->setQuizCover($photoContent);
            }

            // check if quiz already exists in database
            if ($quizRepository->findBy(['quizName' => $quiz->getQuizName()])) {
                $this->addFlash('error', 'Quiz already exists in database!');
                return $this->redirectToRoute('app_quiz_new');
            }

            $quizRepository->save($quiz, true);

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
            'questions' => $question,
        ]);
    }

    #[Route('/quizShow/{id}', name: 'quiz_show_image')]
    public function showPhoto(Quiz $quiz): Response
    {

        $image = stream_get_contents($quiz->getQuizCover());
        return new Response($image, 200, ['Content-Type' => 'image/jpeg']);
    }

    #[Route('/{quizId}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('/{quizId}/edit', name: 'app_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, QuizRepository $quizRepository): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get uploaded file for photo field
            $photoFile = $form->get('quizCover')->getData();

            if ($photoFile) {
                // open file and get contents as string
                $photoContent = file_get_contents($photoFile->getRealPath());
                $quiz->setQuizCover($photoContent);
            }

            $quizRepository->save($quiz, true);

            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }


    #[Route('/{quizId}', name: 'app_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, QuizRepository $quizRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getQuizId(), $request->request->get('_token'))) {
            $quizRepository->remove($quiz, true);
        }

        return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
    }


}
