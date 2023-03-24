<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuizQuestionRepository;
/**
 * QuizQuestion
 *
 * @ORM\Table(name="QuizQuestion")
 * @ORM\Entity(repositoryClass="App\Repository\QuizQuestionRepository")
 */
#[ORM\Entity(repositoryClass: QuizQuestionRepository::class)]
class QuizQuestion
{
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    #[ORM\JoinColumn(name: "quiz_id", referencedColumnName: "quiz_id")]
    private $quiz;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(name: "question_id", referencedColumnName: "question_id")]
    private $question;

}