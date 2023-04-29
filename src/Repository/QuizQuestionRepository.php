<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizQuestion>
 *
 * @method QuizQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizQuestion[]    findAll()
 * @method QuizQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizQuestion::class);
    }

    public function getNextQuestion(Quiz $quiz, Question $question)
    {
        $quizQuestions = $quiz->getQuizQuestions();
        $questions = [];
        foreach ($quizQuestions as $qq) {
            if ($qq->getQuestion() !== $question) {
                $questions[] = $qq->getQuestion()->getId();
            }
        }

        $qb = $this->createQueryBuilder('qq');
        $qb->where('qq.quiz = :quiz')
            ->andWhere('qq.question NOT IN (:questions)')
            ->setParameter('quiz', $quiz)
            ->setParameter('questions', $questions)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function save(QuizQuestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QuizQuestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return QuizQuestion[] Returns an array of QuizQuestion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuizQuestion
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
