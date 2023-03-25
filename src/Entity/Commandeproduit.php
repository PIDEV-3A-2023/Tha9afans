<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeproduitRepository;

/**
 * Panier
 *
 * @ORM\Table(name="Commandeproduit")
 * @ORM\Entity(repositoryClass="App\Repository\CommandeproduitRepository")
 */
#[ORM\Entity(repositoryClass: Commandeproduit::class)]

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




    #[ORM\ManyToOne(targetEntity: Commande::class)]
    private ?Commande $idCommende=null;




}
