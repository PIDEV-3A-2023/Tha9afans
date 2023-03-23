<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity
 */
class Question
{
    /**
     * @var int
     *
     * @ORM\Column(name="question_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $questionId;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=200, nullable=false)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=200, nullable=false)
     */
    private $answer;

    /**
     * @var int
     *
     * @ORM\Column(name="timer", type="integer", nullable=false)
     */
    private $timer;

    /**
     * @var string
     *
     * @ORM\Column(name="first_possible_answer", type="string", length=50, nullable=false)
     */
    private $firstPossibleAnswer;

    /**
     * @var string
     *
     * @ORM\Column(name="second_possible_answer", type="string", length=50, nullable=false)
     */
    private $secondPossibleAnswer;

    /**
     * @var string
     *
     * @ORM\Column(name="third_possible_answer", type="string", length=50, nullable=false)
     */
    private $thirdPossibleAnswer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="blob", length=0, nullable=true)
     */
    private $image;

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
