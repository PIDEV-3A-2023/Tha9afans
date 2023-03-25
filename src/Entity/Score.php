<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ScoreRepository;

/**
 * Score
 *
 * @ORM\Table(name="Score")
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $score;

    #[ORM\Column(name: 'times_played', type: 'integer')]
    private int $timesPlayed;

    #[ORM\ManyToOne(targetEntity: 'Quiz')]
    #[ORM\JoinColumn(name: 'quiz_id', referencedColumnName: 'quiz_id')]
    private Quiz $quiz;

    #[ORM\ManyToOne(targetEntity: 'Personnes')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private Personnes $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getTimesPlayed(): ?int
    {
        return $this->timesPlayed;
    }

    public function setTimesPlayed(int $timesPlayed): self
    {
        $this->timesPlayed = $timesPlayed;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getIdUser(): ?Personnes
    {
        return $this->idUser;
    }

    public function setIdUser(?Personnes $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

}