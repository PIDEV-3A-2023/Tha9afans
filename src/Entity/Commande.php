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



}
