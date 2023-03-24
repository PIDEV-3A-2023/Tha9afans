<?php

namespace App\Entity;
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

    #[ORM\Column(type: 'blob', nullable: true)]
    private $image;

}