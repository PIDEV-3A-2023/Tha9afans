<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;

/**
 * Paniergit
 *
 * @ORM\Table(name="Commande")
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
#[ORM\Entity(repositoryClass: Commande::class)]

class Commande
{

    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id=null;



    #[ORM\Column]
    #[ORM\GeneratedValue]// auto increment
    private ?\DateTime $datecommande=null;


    #[ORM\Column]
    private ?float $total=null;



    #[ORM\ManyToOne(targetEntity: Personnes::class)]
    private ?Personnes $idUser=null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecommande(): ?\DateTimeInterface
    {
        return $this->datecommande;
    }

    public function setDatecommande(\DateTimeInterface $datecommande): self
    {
        $this->datecommande = $datecommande;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

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
