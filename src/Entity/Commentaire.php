<?php

namespace App\Entity;

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


    #[ORM\Column(type: 'date')]
    private ?DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Personnes $idUser=null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Evenement $idEvent=null;


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


    public function getDate(): ?DateTime
    {
        return $this->date;
    }


    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }


    public function getIdUser(): ?Personnes
    {
        return $this->idUser;
    }

    public function setIdUser(?Personnes $idUser): void
    {
        $this->idUser = $idUser;
    }

    public function getIdEvent(): ?Evenement
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evenement $idEvent): void
    {
        $this->idEvent = $idEvent;
    }




}
