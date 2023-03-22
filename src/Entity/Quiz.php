<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity
 */
class Quiz
{
    /**
     * @var int
     *
     * @ORM\Column(name="quiz_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $quizId;

    /**
     * @var string
     *
     * @ORM\Column(name="quiz_name", type="string", length=50, nullable=false)
     */
    private $quizName;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_questions", type="integer", nullable=false)
     */
    private $nbrQuestions;

    /**
     * @var string|null
     *
     * @ORM\Column(name="quiz_cover", type="blob", length=0, nullable=true)
     */
    private $quizCover;

    /**
     * @var string
     *
     * @ORM\Column(name="quiz_description", type="string", length=200, nullable=false)
     */
    private $quizDescription;


}
