<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FactureRepository;

/**
 * Panier
 *
 * @ORM\Table(name="Facture")
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */
#[ORM\Entity(repositoryClass: Facture::class)]

class Facture
{

    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id=null;



    #[ORM\Column]
    private ?\DateTime $datefacture=null;



    #[ORM\Column]
    private ?float $tva=null;



    #[ORM\Column]
    private ?string $refrancefacture=null;


    #[ORM\ManyToOne(targetEntity: Commande::class)]
    private ?Commande $idCommende=null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatefacture(): ?\DateTimeInterface
    {
        return $this->datefacture;
    }

    public function setDatefacture(\DateTimeInterface $datefacture): self
    {
        $this->datefacture = $datefacture;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getRefrancefacture(): ?string
    {
        return $this->refrancefacture;
    }

    public function setRefrancefacture(string $refrancefacture): self
    {
        $this->refrancefacture = $refrancefacture;

        return $this;
    }

    public function getIdCommende(): ?Commande
    {
        return $this->idCommende;
    }

    public function setIdCommende(?Commande $idCommende): self
    {
        $this->idCommende = $idCommende;

        return $this;
    }



}
