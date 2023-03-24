<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panierproduit
 *
 * @ORM\Table(name="panierproduit", indexes={@ORM\Index(name="idproduit", columns={"id_produit"}), @ORM\Index(name="fkidpanier", columns={"id_panier"})})
 * @ORM\Entity
 */
class Panierproduit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
   // private $id;

    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id=null;


    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
   // private $quantity;
    #[ORM\Column]
    private ?int $quantity=null;


    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_panier", referencedColumnName="id")
     * })
     */
    // private $idPanier;
    #[ORM\ManyToOne(targetEntity: Panier::class)]
    private ?Panier $idPanier=null;



    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id")
     * })
     */
    // private $idProduit;
    #[ORM\ManyToOne(targetEntity: Produit::class)]
    private ?Produit $idProduit=null;




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
