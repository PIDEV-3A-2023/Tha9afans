<?php

namespace App\Entity;
use App\Repository\EvenementRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 10000)]
    private ?string $description = null;

    #[ORM\Column(type: 'date')]
    private ?DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?Personnes $idCreateur=null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column()]
    private ?int $nbParticipants = null;

    #[ORM\Column()]
    private ?int $nbAime = null;

    #[ORM\Column()]
    private ?int $prix = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?CategorieEvenement $idCategorie=null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }


    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    public function getDate(): ?DateTime
    {
        return $this->date;
    }


    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }


    public function getIdCreateur(): ?Personnes
    {
        return $this->idCreateur;
    }


    public function setIdCreateur(?Personnes $idCreateur): void
    {
        $this->idCreateur = $idCreateur;
    }


    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): void
    {
        $this->localisation = $localisation;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbParticipants;
    }


    public function setNbParticipants(?int $nbParticipants): void
    {
        $this->nbParticipants = $nbParticipants;
    }


    public function getNbAime(): ?int
    {
        return $this->nbAime;
    }

    public function setNbAime(?int $nbAime): void
    {
        $this->nbAime = $nbAime;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }


    public function setPrix(?int $prix): void
    {
        $this->prix = $prix;
    }


    public function getIdCategorie(): ?CategorieEvenement
    {
        return $this->idCategorie;
    }


    public function setIdCategorie(?CategorieEvenement $idCategorie): void
    {
        $this->idCategorie = $idCategorie;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdCreateur(): ?int
    {
        return $this->idCreateur;
    }

    public function setIdCreateur(int $idCreateur): self
    {
        $this->idCreateur = $idCreateur;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbParticipants;
    }

    public function setNbParticipants(int $nbParticipants): self
    {
        $this->nbParticipants = $nbParticipants;

        return $this;
    }

    public function getNbAime(): ?int
    {
        return $this->nbAime;
    }

    public function setNbAime(int $nbAime): self
    {
        $this->nbAime = $nbAime;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getIdCategorie(): ?CategorieEvenement
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?CategorieEvenement $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }


}
