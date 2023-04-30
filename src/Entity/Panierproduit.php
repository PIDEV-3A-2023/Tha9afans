<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PanierproduitRepository;


/**
 * Panierproduit
 *
 * @ORM\Table(name="Panierproduit")
 * @ORM\Entity(repositoryClass="App\Repository\PanierproduitRepository")
 */
#[ORM\Entity(repositoryClass: PanierproduitRepository::class)]

class Panierproduit
{

    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id;



    #[ORM\Column]
    private ?int $quantity;







    /*#[ORM\ManyToOne(targetEntity: Panier::class)]
    private ?Panier $idPanier=null;*/

    #[ORM\ManyToOne(targetEntity: Panier::class)]
    #[ORM\JoinColumn(name: 'id_panier', referencedColumnName: 'id')]
    private ?Panier $idPanier;

  /*  #[ORM\ManyToOne(targetEntity: Produit::class)]
    private ?Produit $idProduit=null;*/

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: 'id_produit', referencedColumnName: 'id')]
    private ?Produit $idProduit;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier): self
    {
        $this->idPanier = $idPanier;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }









}
