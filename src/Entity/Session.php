<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;

use App\Repository\SessionRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
/**
 * Session
 *
 * @ORM\Table(name="Session")
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 */
#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;
    #[ORM\Column(length: 255)]
    private ?string $parlant = null;

    #[ORM\Column(type: 'time')]
    private ?DateTime $debit = null;

    #[ORM\Column(type: 'time')]
    private ?DateTime $fin = null;


    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?Evenement $idEvenement=null;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getTitre(): ?string
    {
        return $this->titre;
    }


    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    public function getParlant(): ?string
    {
        return $this->parlant;
    }


    public function setParlant(?string $parlant): void
    {
        $this->parlant = $parlant;
    }


    public function getDebit(): ?DateTime
    {
        return $this->debit;
    }


    public function setDebit(?DateTime $debit): void
    {
        $this->debit = $debit;
    }


    public function getFin(): ?DateTime
    {
        return $this->fin;
    }


    public function setFin(?DateTime $fin): void
    {
        $this->fin = $fin;
    }


    public function getIdEvenement(): ?Evenement
    {
        return $this->idEvenement;
    }


    public function setIdEvenement(?Evenement $idEvenement): void
    {
        $this->idEvenement = $idEvenement;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getParlant(): ?string
    {
        return $this->parlant;
    }

    public function setParlant(string $parlant): self
    {
        $this->parlant = $parlant;

        return $this;
    }

    public function getDebit(): ?\DateTimeInterface
    {
        return $this->debit;
    }

    public function setDebit(\DateTimeInterface $debit): self
    {
        $this->debit = $debit;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getIdEvenement(): ?Evenement
    {
        return $this->idEvenement;
    }

    public function setIdEvenement(?Evenement $idEvenement): self
    {
        $this->idEvenement = $idEvenement;

        return $this;
    }


}
