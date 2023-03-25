<?php

namespace App\Entity;

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



}
