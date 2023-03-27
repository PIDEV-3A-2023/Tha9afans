<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuizRepository;


/**
 * Quiz
 *
 * @ORM\Table(name="Quiz")
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "quiz_id", type: "integer", nullable: false)]
    private $quizId;

    #[ORM\Column(name: "quiz_name", type: "string", length: 50, nullable: false)]
    private $quizName;

    #[ORM\Column(name: "nbr_questions", type: "integer", nullable: false)]
    private $nbrQuestions;

    #[ORM\Column(name: "quiz_cover", type: "blob", length: 0, nullable: true)]
    private $quizCover;

    #[ORM\Column(name: "quiz_description", type: "string", length: 200, nullable: false)]
    private $quizDescription;

    public function getQuizId(): ?int
    {
        return $this->quizId;
    }

    public function getQuizName(): ?string
    {
        return $this->quizName;
    }

    public function setQuizName(string $quizName): self
    {
        $this->quizName = $quizName;

        return $this;
    }

    public function getNbrQuestions(): ?int
    {
        return $this->nbrQuestions;
    }

    public function setNbrQuestions(int $nbrQuestions): self
    {
        $this->nbrQuestions = $nbrQuestions;

        return $this;
    }

    public function getQuizCover()
    {
        return $this->quizCover;
    }

    public function setQuizCover($quizCover): self
    {
        $this->quizCover = $quizCover;

        return $this;
    }

    public function getQuizDescription(): ?string
    {
        return $this->quizDescription;
    }

    public function setQuizDescription(string $quizDescription): self
    {
        $this->quizDescription = $quizDescription;

        return $this;
    }

}


