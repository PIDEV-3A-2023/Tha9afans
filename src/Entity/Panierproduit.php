<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PanierproduitRepository;

/**
 * Panier
 *
 * @ORM\Table(name="Panierproduit")
 * @ORM\Entity(repositoryClass="App\Repository\PanierproduitRepository")
 */
#[ORM\Entity(repositoryClass: Panierproduit::class)]

class Panierproduit
{

    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id=null;



    #[ORM\Column]
    private ?int $quantity=null;



    #[ORM\ManyToOne(targetEntity: Panier::class)]
    private ?Panier $idPanier=null;



    #[ORM\ManyToOne(targetEntity: Produit::class)]
    private ?Produit $idProduit=null;





}
