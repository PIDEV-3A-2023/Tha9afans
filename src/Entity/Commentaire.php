<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Repository\CommentaireRepository;
/**
 * Commentaire
 *
 * @ORM\Table(name="Commentaire")
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $commentaire = null;


    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?User $User=null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Evenement $evenement=null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }


    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
    }
    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): void
    {
        $this->User = $User;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): void
    {
        $this->evenement = $evenement;
    }
}
