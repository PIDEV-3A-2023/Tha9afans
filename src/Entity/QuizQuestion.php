<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuizQuestion
 *
 * @ORM\Table(name="quiz_question", indexes={@ORM\Index(name="quiz", columns={"quiz_id"}), @ORM\Index(name="question", columns={"question_id"})})
 * @ORM\Entity
 */
class QuizQuestion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Quiz
     *
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quiz_id", referencedColumnName="quiz_id")
     * })
     */
    private $quiz;

    /**
     * @var \Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="question_id")
     * })
     */
    private $question;


}
