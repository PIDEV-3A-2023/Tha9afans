<?php

namespace App\Entity;

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


}
