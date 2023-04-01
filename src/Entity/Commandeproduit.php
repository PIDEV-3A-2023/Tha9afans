<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeproduitRepository;

/**
 * Commandeproduit
 *
 * @ORM\Table(name="Commandeproduit")
 * @ORM\Entity(repositoryClass="App\Repository\CommandeproduitRepository")
 */
#[ORM\Entity(repositoryClass: CommandeproduitRepository::class)]

class Commandeproduit
{

    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id=null;


    #[ORM\Column]
    private ?int $quantite=null;



    #[ORM\Column]
    private ?int $idProduit=null;




    /*#[ORM\ManyToOne(targetEntity: Commande::class)]
    private ?Commande $idCommende=null;*/


    #[ORM\ManyToOne(targetEntity: Commande::class)]
    #[ORM\JoinColumn(name: 'id_commende', referencedColumnName: 'id')]
    private ?Commande $idCommende=null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(int $idProduit): self
    {
        $this->idProduit = $idProduit;

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
