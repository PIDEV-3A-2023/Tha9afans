<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuestionRepository;


/**
 * Question
 *
 * @ORM\Table(name="Question")
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $questionId;

    #[ORM\Column(type: 'string', length: 200)]
    private $question;

    #[ORM\Column(type: 'string', length: 200)]
    private $answer;

    #[ORM\Column(type: 'integer')]
    private $timer;

    #[ORM\Column(name: 'first_possible_answer', type: 'string', length: 50)]
    private $firstPossibleAnswer;

    #[ORM\Column(name: 'second_possible_answer', type: 'string', length: 50)]
    private $secondPossibleAnswer;

    #[ORM\Column(name: 'third_possible_answer', type: 'string', length: 50)]
    private $thirdPossibleAnswer;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private  $image;




    public function getQuestionId(): ?int
    {
        return $this->questionId;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getTimer(): ?int
    {
        return $this->timer;
    }

    public function setTimer(int $timer): self
    {
        $this->timer = $timer;

        return $this;
    }

    public function getFirstPossibleAnswer(): ?string
    {
        return $this->firstPossibleAnswer;
    }

    public function setFirstPossibleAnswer(string $firstPossibleAnswer): self
    {
        $this->firstPossibleAnswer = $firstPossibleAnswer;

        return $this;
    }

    public function getSecondPossibleAnswer(): ?string
    {
        return $this->secondPossibleAnswer;
    }

    public function setSecondPossibleAnswer(string $secondPossibleAnswer): self
    {
        $this->secondPossibleAnswer = $secondPossibleAnswer;

        return $this;
    }

    public function getThirdPossibleAnswer(): ?string
    {
        return $this->thirdPossibleAnswer;
    }

    public function setThirdPossibleAnswer(string $thirdPossibleAnswer): self
    {
        $this->thirdPossibleAnswer = $thirdPossibleAnswer;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

}

